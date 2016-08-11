<?php

namespace RoyalCopenhagen\Noshi\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Directory\WriteInterface;

/**
 * Cms Page Model
 *
 * @method \RoyalCopenhagen\Noshi\Model\ResourceModel\Noshi _getResource()
 * @method \RoyalCopenhagen\Noshi\Model\ResourceModel\Noshi getResource()
 */

class Noshi extends \Magento\Framework\Model\AbstractModel
{
    const IMAGE_NAME = 'image_name';
    /**
     * Visibility state
     */
    const VISIBILITY_HIDDEN = 0;
    const VISIBILITY_SHOW = 1;
    /**
     * Relative path to folder to store wrapping image to
     */
    const IMAGE_PATH = 'noshi/';

    /**
     * Relative path to folder to store temporary wrapping image to
     */
    const IMAGE_TMP_PATH = 'tmp/noshi/';

    /**
     * Permitted extensions for wrapping image
     *
     * @var array
     */
    protected $_imageAllowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var WriteInterface
     */
    protected $_mediaDirectory;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $_uploaderFactory;

    /**
     * Noshi constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, 
        array $data = []
    )
    {
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->_uploaderFactory = $uploaderFactory;
        $this->_storeManager = $storeManager;
        parent::__construct(
            $context, 
            $registry, 
            $resource, 
            $resourceCollection, 
            $data
        );
    }
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('RoyalCopenhagen\Noshi\Model\ResourceModel\Noshi');
    }

    /**
     * Getting Visibilities
     * 
     * @return array
     */
    public static function getVisibilities()
    {
        return [
            self::VISIBILITY_HIDDEN => __('Hidden'),
            self::VISIBILITY_SHOW => __('Visible')
        ];
    }
    
    public function beforeSave()
    {

        if(!$this->isValidNoshiCode($this->getCode())) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('The Noshi code contains capital letters or disallowed symbols.')
            );
        }
        if ($this->hasTmpImage()) {
            $baseImageName = $this->getTmpImage();
            $sourcePath = self::IMAGE_TMP_PATH . $baseImageName;
            $destPath = self::IMAGE_PATH . $baseImageName;
            if ($this->_mediaDirectory->isFile($sourcePath)) {
                $this->_mediaDirectory->renameFile($sourcePath, $destPath);
                $this->setData('image', $baseImageName);
            }
        }
        return parent::beforeSave();
    }

    /**
     * @param $code
     * @return int
     */
    protected function isValidNoshiCode($code)
    {
        return preg_match('/^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/', $code);
    }

    public function getNoshiItembyCode($code)
    {
        $this->_getResource()->_loadNoshiByCode($code);
    }

    /**
     * @param $code
     * @return string
     */
    public function checkVisibleNoshiCode($code)
    {
        return $this->_getResource()->_loadVisibleNoshiByCode($code);
    }
    /**
     * @param $code
     * @return \Magento\Framework\DB\Select
     */
    public function checkNoshiCode($code)
    {
        return $this->_getResource()->_loadNoshiByCode($code);
    }

    /**
     * Set Noshi image
     *
     * @param string|null|\Magento\MediaStorage\Model\File\Uploader $value
     * @return $this
     */
    public function setImage($value)
    {
        //in the current version should be used instance of \Magento\MediaStorage\Model\File\Uploader
        if ($value instanceof \Magento\Framework\File\Uploader) {
            $value->save($this->_mediaDirectory->getAbsolutePath(self::IMAGE_PATH));
            $value = $value->getUploadedFileName();
        }
        $this->setData('image', $value);
        return $this;
    }

    /**
     * Set temporary Noshi image
     *
     * @param string|null|\Magento\MediaStorage\Model\File\Uploader $value
     * @return $this
     */
    public function setTmpImage($value)
    {
        //in the current version should be used instance of \Magento\MediaStorage\Model\File\Uploader
        if ($value instanceof \Magento\Framework\File\Uploader) {
            // Delete previous temporary image if exists
            $this->unsTmpImage();
            $value->save($this->_mediaDirectory->getAbsolutePath(self::IMAGE_TMP_PATH));
            $value = $value->getUploadedFileName();
        }
        $this->setData('tmp_image', $value);
        // Override gift wrapping image name
        $this->setData('image', $value);
        return $this;
    }

    /**
     * Attach uploaded image to wrapping
     *
     * @param string $imageFieldName
     * @param bool $isTemporary
     * @return $this
     */
    public function attachUploadedImage($imageFieldName, $isTemporary = false)
    {
        $isUploaded = true;
        try {
            /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
            $uploader = $this->_uploaderFactory->create(['fileId' => $imageFieldName]);
            $uploader->setAllowedExtensions($this->_imageAllowedExtensions);
            $uploader->setAllowRenameFiles(true);
            $uploader->setAllowCreateFolders(true);
            $uploader->setFilesDispersion(false);
        } catch (\Exception $e) {
            $isUploaded = false;
        }
        if ($isUploaded) {
            if ($isTemporary) {
                $this->setTmpImage($uploader);
            } else {
                $this->setImage($uploader);
            }
        }
        return $this;
    }

    /**
     * Delete temporary wrapping image
     *
     * @return $this
     */
    public function unsTmpImage()
    {
        if ($this->hasTmpImage()) {
            $tmpImagePath = self::IMAGE_TMP_PATH . $this->getTmpImage();
            if ($this->_mediaDirectory->isExist($tmpImagePath)) {
                $this->_mediaDirectory->delete($tmpImagePath);
            }
            $this->unsetData('tmp_image');
        }
        return $this;
    }

    /**
     * Retrieve Noshi image url
     * Function returns url of a temporary wrapping image if it exists
     * 
     * @see \RoyalCopenhagen\Noshi\Block\Adminhtml\Noshi\Helper\Image
     * 
     * @return string|null
     */
    public function getImageUrl()
    {
        if ($this->getTmpImage()) {
            return $this->_storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ) . self::IMAGE_TMP_PATH . $this->getTmpImage();
        }
        if ($this->getImage()) {
            return $this->_storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ) . self::IMAGE_PATH . $this->getImage();
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getImageName()
    {
        return $this->getData(self::IMAGE_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setImageName($name)
    {
        return $this->setData(self::IMAGE_NAME, $name);
    }
}