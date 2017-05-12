<?php

namespace Omnipay\Wirecard\Extend;

/**
 * Extends the Item class to support additional properties
 * supported by Wirecard.
 */

use Omnipay\Common\Item as CommonItem;

class Item extends CommonItem implements ItemInterface
{
   /**
     * {@inheritDoc}
     */
    public function getArticleNumber()
    {
        return $this->getParameter('articleNumber');
    }

    /**
     * {@inheritDoc}
     */
    public function setArticleNumber($value)
    {
        return $this->setParameter('articleNumber', $value);
    }

   /**
     * {@inheritDoc}
        */
    public function getImageUrl()
    {
        return $this->getParameter('imageUrl');
    }

    /**
     * {@inheritDoc}
     */
    public function setImageUrl($value)
    {
        return $this->setParameter('imageUrl', $value);
    }

   /**
     * {@inheritDoc}
        */
    public function getNetAmount()
    {
        return $this->getParameter('netAmount');
    }

    /**
     * {@inheritDoc}
     */
    public function setNetAmount($value)
    {
        return $this->setParameter('netAmount', $value);
    }

   /**
     * {@inheritDoc}
        */
    public function getTaxAmount()
    {
        return $this->getParameter('taxAmount');
    }

    /**
     * {@inheritDoc}
     */
    public function setTaxAmount($value)
    {
        return $this->setParameter('taxAmount', $value);
    }

   /**
     * {@inheritDoc}
        */
    public function getTaxRate()
    {
        return $this->getParameter('taxRate');
    }

    /**
     * {@inheritDoc}
     */
    public function setTaxRate($value)
    {
        return $this->setParameter('taxRate', $value);
    }
}
