<?php declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Service;

use InvalidArgumentException;
use Shopware\Core\Content\Mail\Service\MailService;
use Shopware\Core\Content\Media\MediaService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\Content\MailTemplate\MailTemplateEntity;

class Email {
    /**
     * Constructor method
     *
     * @param MailService $mailService
     * @param EntityRepository $mailTemplate
     * @param EntityRepository $mediaRepository
     */
    public function __construct(
        private MailService $mailService,
        private EntityRepository $mailTemplate,
        private EntityRepository $mediaRepository
    ) {}

    /**
     * Send an email
     *
     * @param array $params
     * @param string $mail_template_id
     * @param SalesChannelContext $context
     * @param string $email_address
     * @param string $email_name
     * @param string $subject
     * @param string $mediaId
     * @param MediaService $mediaService
     * @return void
     */
    public function send(array $params, string $mail_template_id, SalesChannelContext $context, string $email_address, string $email_name, string $subject, string $mediaId, MediaService $mediaService): void
    {
        $mail_template = $this->getTemplate($mail_template_id, $context);
        if (null === $mail_template) {
            return;
        }
        $this->changeContentVarToData($params, $mail_template);

        // Upload file
        $attachments = [];
        if (!empty($mediaId)) {
            $criteria = new Criteria([$mediaId]);
            $media = $this->mediaRepository->search($criteria, $context->getContext())->first();
            $attachments[] = $mediaService->getAttachment($media, $context->getContext());
        }

        $data = new ParameterBag();

        // Create array of recipients
        $recipientEmails = explode(',', $email_address);
        $recipients = [];
        foreach ($recipientEmails as $recipient) {
            $recipients[trim($recipient)] = $email_name;
        }
        $data->set(
            'recipients',
            $recipients
        );

        if($subject == '') {
            $subject = $mail_template->getSubject();
        }

        $data->set('senderName', $mail_template->getSenderName());
        $data->set('contentHtml', $mail_template->getContentHtml());
        $data->set('contentPlain', $mail_template->getContentPlain());
        $data->set('subject', $subject);
        $data->set('salesChannelId', $context->getSalesChannel()->getId());
        $data->set('binAttachments', $attachments);

        $this->mailService->send(
            $data->all(),
            $context->getContext()
        );
    }

    /**
     * Change mail content from {{var}} to values from the form
     *
     * @param array $params
     * @param MailTemplateEntity $mail_template
     * @return void
     */
    private function changeContentVarToData(array $params, MailTemplateEntity &$mail_template): void
    {
        $html_text = $mail_template->getContentHtml();
        $plain_text = $mail_template->getContentPlain();
        foreach($params as $key => $param) {
            $html_text = str_replace("{{{$key}}}", $param, $html_text);
            $plain_text = str_replace("{{{$key}}}", $param, $plain_text);
        }
        $mail_template->setContentHtml($html_text);
        $mail_template->setContentPlain($plain_text);
    }

    /**
     * Get template from repository by provided id
     *
     * @param string $mail_template_id
     * @param SalesChannelContext $context
     * @return MailTemplateEntity|null
     */
    private function getTemplate(string $mail_template_id, SalesChannelContext $context): ?MailTemplateEntity
    {
        $criteria = new Criteria([$mail_template_id]);

        try {
            $mail_template = $this->mailTemplate->search($criteria, $context->getContext())->first();
        } catch(\Exception $e) {
            throw new InvalidArgumentException('Could not find mail template with ID: '.$mail_template_id);
        }
        return $mail_template;

    }
}
