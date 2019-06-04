<?php
$reviewsCollection = Mage::getModel('review/review')->getCollection()
           ->addStoreFilter(Mage::app()->getStore()->getId())
           ->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
           ->addEntityFilter('product', $_product->getId())
           ->setDateOrder()
           ->setCurPage(1)
           ->setPageSize(1)
           ->load()
           ->addRateVotes();
       $item = $reviewsCollection->getFirstItem();
       $votes = array();
       $productReview = array();
       if ($item->getRatingVotes()) {
           foreach ($item->getRatingVotes() as $vote) {
               $votes[] = $vote->getPercent();
           }
       }
       $ratingValue = count($votes) ? (array_sum($votes) / count($votes)) : 0;
       if ($ratingValue) {
           $productReview = array(
               '@type' => 'Review',
               'reviewRating' => array(
                   '@type' => 'Rating',
                   'ratingValue' => $ratingValue,
                   'bestRating' => 100,
               ),
               'author' => array(
                   '@type' => 'Person',
                   'name' => $item->getNickname()
               )
           );
       }
?>
    <div itemscope itemprop="review" itemtype="https://schema.org/Review">
<?php if(count($productReview)): ?>
  
        <meta itemprop="author" content="<?php echo $productReview['author']['name']; ?>"/>
      
    <?php else: ?>
        <meta itemprop="author" content="<?php echo 'https://schema.org/author'; ?>"/>
<?php endif;?>
</div>