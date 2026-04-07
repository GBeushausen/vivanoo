<?php declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Util\Random;
use Shopware\Core\Content\Media\File\MediaFile;
use Shopware\Core\Content\Media\File\FileSaver;
use Shopware\Core\Content\Media\File\FileNameProvider;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Upload file service
 */
class File {
    private const SUPPORTED_EXTENSIONS = ['gif', 'png', 'jpg', 'jpeg', 'pdf', 'stp', 'dfx'];

    /**
     * Constructor
     *
     * @param EntityRepository $mediaRepository
     * @param FileSaver $mediaUpdated
     * @param FileNameProvider $fileNameProvider
     * @author MP
     */
    public function __construct(
        private EntityRepository $mediaRepository,
        private FileSaver $mediaUpdated,
        private FileNameProvider $fileNameProvider
    ) {}
    
    /**
     * Upload image
     *
     * @param array $files
     * @param string $folder_id
     * @return string
     * @author MP
     */
    public function upload(array $files, string $folder_id): string 
    {
        $context = Context::createDefaultContext();

        $mediaId = '';
        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();
            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            if (!in_array($ext, self::SUPPORTED_EXTENSIONS) ) {
                $error = true;
                $message = 'Invalid Extension';
            } else {
                $fileName = $fileName . Random::getInteger(100, 1000);
                $mediaId = Uuid::randomHex();
                $media = [
                    [
                        'id' => $mediaId,
                        'name' => $fileName,
                        'fileName' => $fileName,
                        'mimeType' => $file->getClientMimeType(),
                        'fileExtension' => $file->guessExtension(),
                        'mediaFolderId' => $folder_id
                     ]
                 ];
                    
                $mediaId = $this->mediaRepository->create($media, Context::createDefaultContext())->getEvents()->getElements()[1]->getIds()[0];
                if (is_array($mediaId)) {
                    $mediaId = $mediaId['mediaId'];
                }

                try {
                    $this->add($file, $fileName, $mediaId, $context);                
                } catch (\Exception $exception) {
                    $fileName = $fileName . Random::getInteger(100, 1000);
                    $this->add($file, $fileName, $mediaId, $context);
                }
            }
        }

        return $mediaId;
    }
    
    public function add(UploadedFile $file, string $fileName, string $mediaId, Context $context): void
    {   
        $this->mediaUpdated->persistFileToMedia(
            new MediaFile(
                $file->getRealPath(),
                $file->getMimeType(),
                $file->guessExtension(),
                $file->getSize()
            ),
            $this->fileNameProvider->provide(
                $fileName,
                $file->getExtension(),
                $mediaId,
                $context
            ),
            $mediaId,
            $context
        );  
    }
}
