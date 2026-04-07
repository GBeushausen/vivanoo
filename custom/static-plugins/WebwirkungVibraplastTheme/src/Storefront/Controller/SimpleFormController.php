<?php declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Storefront\Controller;

use Shopware\Core\Content\Media\File\FileNameProvider;
use Shopware\Core\Content\Media\File\FileSaver;
use Shopware\Core\Content\Media\MediaService;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Log\Package;
use AllowDynamicProperties;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\Framework\Validation\Exception\ConstraintViolationException;
use Shopware\Storefront\Framework\Captcha\Exception\CaptchaInvalidException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Shopware\Core\Content\Mail\Service\MailService;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Controller\StorefrontController;

use Symfony\Contracts\Translation\TranslatorInterface;
use Webwirkung\VibraplastTheme\Service\Email;
use Webwirkung\VibraplastTheme\Service\File;

#[Route(defaults: ['_routeScope' => ['storefront']])]
#[Package('storefront')]
#[AllowDynamicProperties]
class SimpleFormController extends StorefrontController
{

    public function __construct(
        private MailService               $mailService,
        private EntityRepository          $mailTemplate,
        private SystemConfigService       $systemConfigService,
        private readonly EntityRepository $cmsSlotRepository,
        private FileSaver                 $mediaUpdater,
        private FileNameProvider          $fileNameProvider,
        private MediaService              $mediaService,
        private EntityRepository          $mediaRepository,
        private EntityRepository          $mediaFolderRepository,
        private TranslatorInterface       $translator,
        private readonly iterable         $captchas,
    )
    {
    }

    #[Route(path: '/send-form-simple', name: 'frontend.form.simple.send', defaults: ['XmlHttpRequest' => true], methods: ['POST'])]
    public function simpleForm(Request $request, RequestDataBag $data, SalesChannelContext $salesChannelContext, Context $context): JsonResponse
    {
        try {
            $this->validateCaptcha($request, $salesChannelContext->getSalesChannelId());

            // Send contact form with upload file
            $params_array = $request->request->all();

            // MEDIA UPLOAD
            $files = $request->files->all();
            $mediaId = '';
            if (
                0 < count($files) &&
                null !== $files['upload_file']
            ) {
                $file = new File($this->mediaRepository, $this->mediaUpdater, $this->fileNameProvider);
                $mediaFolderId = $this->getMediaFolderId('Form Upload', $context);
                $mediaId = $file->upload($files, $mediaFolderId);
            }

            $slotConfigs = $this->getSlotConfigs(
                $salesChannelContext,
                $data->get('slotId')
            );

            $receiver = (isset($slotConfigs['mailReceiver']['value'][0])) ? $slotConfigs['mailReceiver']['value'][0] : null;

            // if receiver empty, get the default receiver from settings
            if (empty($receiver)) {
                $receiver = $this->getBasicInformation('core.basicInformation.email', $salesChannelContext);
            }

            $subject = '';
            $receiver_email = $receiver;
            $system_shopName = $this->getBasicInformation('core.basicInformation.shopName', $salesChannelContext);

            $email = new Email($this->mailService, $this->mailTemplate, $this->mediaRepository);

            // contact form template
            $email->send($params_array, '018d3f4cd58d72fcb577cbb2bb6580be', $salesChannelContext, $receiver_email, $system_shopName, $subject, $mediaId, $this->mediaService);
            $response[] = [
                'type' => 'success',
                'alert' => !empty($slotConfigs['confirmationText']['value']) ?
                    $slotConfigs['confirmationText']['value'] : $this->translator->trans('form.form-simple.message.success'),
            ];
        } catch (CaptchaInvalidException $captchaException) {
            // Handle captcha validation failures
            $response[] = [
                'type' => 'danger',
                'alert' => $this->renderView('@Storefront/storefront/utilities/alert.html.twig', [
                    'type' => 'danger',
                    'list' => [$this->translator->trans('form.form-simple.message.error')],
                ]),
            ];
        } catch (ConstraintViolationException $formViolations) {
            $violations = [];
            foreach ($formViolations->getViolations() as $violation) {
                $violations[] = $violation->getMessage();
            }
            $response[] = [
                'type' => 'danger',
                'alert' => $this->renderView('@Storefront/storefront/utilities/alert.html.twig', [
                    'type' => 'danger',
                    'list' => $violations,
                ]),
            ];
        }

        return new JsonResponse($response);

    }

    private function getSlotConfigs(
        SalesChannelContext $context,
        ?string             $slotId = null
    ): array
    {

        if (!$slotId) {
            return [];
        }

        $criteria = new Criteria([$slotId]);
        $slot = $this->cmsSlotRepository->search($criteria, $context->getContext());

        return $slot->getEntities()->first()->getTranslated()['config'];
    }

    /**
     * Get basic information from settings value by field name
     *
     * @param string $field
     * @param SalesChannelContext $salesChannelContext
     * @return string
     */
    private function getBasicInformation(string $field, SalesChannelContext $salesChannelContext): string
    {
        return $this->systemConfigService->get($field, $salesChannelContext->getSalesChannel()->getId());
    }

    private function getMediaFolderId(string $folderName, Context $context): string
    {
        $folderCriteria = new Criteria();
        $folderCriteria->addFilter(new EqualsFilter('name', $folderName));
        return $this->mediaFolderRepository->search($folderCriteria, $context)->getEntities()->first()->getId();
    }

    private function validateCaptcha(Request $request, string $salesChannelId): void
    {
        $activeCaptchas = (array)($this->systemConfigService->get('core.basicInformation.activeCaptchasV2', $salesChannelId) ?? []);
        foreach ($this->captchas as $captcha) {
            $captchaConfig = $activeCaptchas[$captcha->getName()] ?? [];
            if (
                $captcha->supports($request, $captchaConfig) && !$captcha->isValid($request, $captchaConfig) && $captcha->shouldBreak()
            ) {
                throw new CaptchaInvalidException($captcha);
            }
        }
    }
}

