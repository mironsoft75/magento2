<?php

namespace RoyalCopenhagen\Noshi\Block\Adminhtml\Noshi\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{

    /**
     * @var \RoyalCopenhagen\Noshi\Model\Source\Visibility
     */
    protected $_visibility;

    /**
     * Form constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \RoyalCopenhagen\Noshi\Model\Source\Visibility $visibility
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \RoyalCopenhagen\Noshi\Model\Source\Visibility $visibility,
        array $data = []
    )
    {

        $this->_visibility = $visibility;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('noshi_item_form');
        $this->setTitle(__('Item Information'));
    }
    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('noshi_item');
        $isElementDisabled = false;
        $actionParams = [];
        if ($model->getId()) {
            $actionParams['entity_id'] = $model->getId();
        }

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getUrl('royal_noshi/noshi/save', $actionParams),
                    'method' => 'post',
                    'field_name_suffix' => 'noshi_item',
                    'enctype' => 'multipart/form-data'
                ],
            ]
        );
        

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Item Information')]);
        $this->_addElementTypes($fieldset);
        
        if ($model->getId()) {
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        }
        $fieldset->addField(
            'name',
            'text',
            ['name' => 'name', 'label' => __('Noshi Item Name'), 'title' => __('Brand Name'), 'required' => true]
        );
        $fieldset->addField(
            'code',
            'text',
            ['name' => 'code', 'label' => __('Code'), 'title' => __('Code'), 'required' => true]
        );
        $fieldset->addField(
            'description',
            'textarea',
            ['name' => 'description', 'label' => __('Description'), 'title' => __('Description'), 'required' => true]
        );
       
        $fieldset->addField(
            'visibility',
            'select',
            [
                'label' => __('Visibility'),
                'title' => __('Visibility'),
                'name' => 'visibility',
                'required' => true,
                'options' => \RoyalCopenhagen\Noshi\Model\Noshi::getVisibilities(),
                'disabled' => $isElementDisabled
            ]
        );
        //Add image filed to our form
        $fieldset->addField(
            'image',
            'image',
            ['name' => 'image_name', 'label' => __('Image'), 'title' => __('Image')]
        );
        
        if ($model->hasTmpImage()) {
            $fieldset->addField('tmp_image', 'hidden', ['name' => 'tmp_image']);
        }

        $this->setForm($form);
        $form->setValues($model->getData());
        $form->setDataObject($model);
        $form->setUseContainer(true);
        return parent::_prepareForm();
    }

    /**
     * Retrieve Additional Element Types
     *
     * @return array
     * @codeCoverageIgnore
     */
    protected function _getAdditionalElementTypes()
    {
        return ['image' => 'RoyalCopenhagen\Noshi\Block\Adminhtml\Noshi\Helper\Image'];
    }
}