<?php

namespace Omnipay\Wirecard\Extend;

/**
 * Extends the Item class to support additional properties
 * supported by Wirecard.
 */

use Omnipay\Common\ItemInterface as CommonItemInterface;

interface ItemInterface extends CommonItemInterface
{
    /**
     * Get the item ArticleNumber.
     * This will probably be the SKU, or part of the SKU.
     * Required.
     */
    public function setArticleNumber($value);

    /**
     * Get the item ArticleNumber.
     */
    public function getArticleNumber();

    /**
     * Set the item image.
     * Optional.
     */
    public function setImageUrl($value);

   /**
     * Set the item image.
    */
    public function getImageUrl();

   /**
    * The item net cost.
    */
    public function setNetAmount($value);

   /**
    * The item net cost.
    */
    public function getNetAmount();

   /**
    * The item tax amount.
    */
    public function setTaxAmount($value);

   /**
    * The item tax amount.
    */
    public function getTaxAmount();

   /**
    * The item tax rate, percantage to 3dp.
    */
    public function setTaxRate($value);

   /**
    * The item tax rate, percantage to 3dp.
    */
    public function getTaxRate();
}
