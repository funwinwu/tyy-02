<?php
class Extrapkg_Contacter_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {   	
		$this->saveProductList();
    	
    	$this->loadLayout();    
		$this->_initLayoutMessages('customer/session');
		$this->renderLayout();
    }
	
		public function opportunityAction()
    {    	
		
		$this->loadLayout();     
		$this->_initLayoutMessages('customer/session');
		$this->renderLayout();
    }
	
	public function feedbackAction()
    {    	
		
		$this->loadLayout();    
		$this->_initLayoutMessages('customer/session');
		$this->renderLayout();
    }
	
	public function corporateAction()
    {    	
		
		$this->loadLayout();   
		$this->_initLayoutMessages('customer/session');
		$this->renderLayout();
    }
    
	public function rmarequestAction()
  {    	
		$this->loadLayout();   
		$this->_initLayoutMessages('customer/session');
		$this->renderLayout();
  }
  
	public function wholesaleAction()
  {    	
		$this->loadLayout();   
		$this->_initLayoutMessages('customer/session');
		$this->renderLayout();
  }
  
	public function postAction()
    {
        $post = $this->getRequest()->getPost();
		Mage::getSingleton('customer/session')->setFormData($post);
		$page = '';
		isset( $post['page'] ) && $page = $post['page'];
//		if( implode( '',Mage::getModel('customer/session')->getData('verify_code' ) ) != $post['validation'] ){
//			Mage::getSingleton('customer/session')->addError(Mage::helper('contacts')->__('Verification code does not match.'));
//			$this->_redirect('*/*/'.$page);
//			return;
//			
//		}
		
		$this->saveProductList();
        $session = Mage::getSingleton('catalog/session');
    	$products_make_offer = $session->getData( "products_make_offer" );
    	$product_collection = array();
    	$product_list_content = "\n";
    	if( !empty( $products_make_offer ) ){   
	        $product_collection = Mage::getModel( "catalog/product" )->getCollection();
	
	        $product_collection->joinField('qty',
	                'cataloginventory/stock_item',
	                'qty',
	                'product_id=entity_id',
	                '{{table}}.stock_id=1',
	                'left');
	
	       $product_collection->addAttributeToSelect('model')
		   ->addAttributeToSelect('name')
		   ->addAttributeToSelect('image');    	
		   	$product_collection->addAttributeToFilter("entity_id",array( 'in' => array(  $products_make_offer['product_id'] ) ) );
		   	$currency = Mage::getSingleton( "contacter/contacter" )->getCurrency()->getCurrencyOptions( Mage::getSingleton( "contacter/contacter" )->getCurrency()->getCurrentCurrencyCode() );
		   	foreach( $product_collection as $_product ){
		   		$product_list_content .= $_product->getName().' ';
		   		$product_list_content .= " ---Model:".$_product->getData('model');
		   		$product_list_content .= " ---Qty:".Mage::getSingleton( "contacter/contacter" )->getOfferQty( $_product->getId() );
		   		$product_list_content .= " ---Price:".$currency['symbol'].Mage::getSingleton( "contacter/contacter" )->getOfferPrice( $_product->getId() );
		   		$product_list_content .= "\n";
		   	}
    	}
        
        if ( $post ) {
            $translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
            $translate->setTranslateInline(false);
            try {
				
                $postObject = new Varien_Object();
                $postObject->setData($post);

                $error = false;
/*
                if (!Zend_Validate::is(trim($post['question']) , 'NotEmpty')) {
                    $error = true;
                }

              */
                if ($error) {
                    throw new Exception();
                }

				isset( $post['title'] ) && $contacter['title'] = $post['title'];
				isset( $post['first_name'] ) && $contacter['first_name'] = $post['first_name'];
				isset( $post['last_name'] ) && $contacter['last_name'] = $post['last_name'];
				isset( $post['country'] ) && $contacter['country'] = $post['country'];
				isset( $post['city'] ) && $contacter['city'] = $post['city'];
				isset( $post['zip'] ) && $contacter['zip'] = $post['zip'];
				isset( $post['conpany_name'] ) && $contacter['conpany_name'] = $post['conpany_name'];
				isset( $post['conpany_address_01'] ) && $contacter['conpany_address_01'] = $post['conpany_address_01'];
				isset( $post['conpany_address_02'] ) && $contacter['conpany_address_02'] = $post['conpany_address_02'];
				isset( $post['business_year'] ) && $contacter['business_year'] = $post['business_year'];
				isset( $post['business_role'] ) && $contacter['business_role'] = $post['business_role'];
				isset( $post['business_nuture'] ) && $contacter['business_nuture'] = $post['business_nuture'];
				isset( $post['no_of_employees'] ) && $contacter['no_of_employees'] = $post['no_of_employees'];
				isset( $post['position'] ) && $contacter['position'] = $post['position'];
				isset( $post['cif'] ) && $contacter['cif'] = $post['cif'];
				isset( $post['tel'] ) && $contacter['tel'] = $post['tel'];
				isset( $post['email'] ) && $contacter['email'] = $post['email'];
				//isset( $post['gift_feedback'] ) && $contacter['gift_feedback'] = $post['gift_feedback'];
				isset( $post['product_code'] ) && $contacter['product_code'] = $post['product_code'];
				isset( $post['subject'] ) && $contacter['subject'] = $post['subject'];
				isset( $post['content'] ) && $contacter['content'] = $post['content'].$product_list_content;
				isset( $post['type'] ) && $contacter['type'] = $post['type'];
				$contacter['created_time'] = time();
				
				if( isset( $post['gift_feedback'] ) ){
					$contacter['gift_feedback'] .= implode( ',',$post['gift_feedback'] );				
				}
				
				$email_data = $post;
				$email_data['comment'] = '';
				if( $email_data['type'] == 1 ){
					$email_data['comment'] .= 'Product Enquiry';
				}else if( $email_data['type'] == 2 ){
					$email_data['comment'] .= 'Business Opportunities';
				}else if( $email_data['type'] == 3 ){
					$email_data['comment'] .= 'Corporate Sales';
				}else if( $email_data['type'] == 4 ){
					$email_data['comment'] .= 'Feedback & Comments';
				}
				
				$name_title = '';
				if( isset( $post['title'] ) ){
					if( $post['title'] == 1 ) $name_title = 'Mr.';
					else if( $post['title'] == 2 ) $name_title = 'Mrs.';
					else if( $post['title'] == 3 ) $name_title = 'Miss';
				}
				
				$email_data['title'] = $name_title;
				/*handle poll start. 20100.16.*/
				if( isset( $post['vote'] ) ){
					foreach( $post['vote'] as $pll_id => $answer_id ){
						$poll = Mage::getModel("poll/poll")->load( $pll_id );
						if ($poll->getId() && !$poll->getClosed() && !$poll->isVoted() && $answer_id) {
							$vote = Mage::getModel('poll/poll_vote')
								->setPollAnswerId($answer_id)
								->setIpAddress(Mage::helper('core/http')->getRemoteAddr(true))
								->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId());

							$poll->addVote($vote);
						}
					}
				}
				if( isset( $post['poll'] ) ){
					$poll_comments = '<br />';
					foreach( $post['poll'] as $pll_title => $poll_comment ){
						if( !empty( $poll_comment ) )
							$poll_comments .= "{$pll_title}:<b>{$poll_comment}</b><br />";
					}
					$email_data['content'] .= $poll_comments;
					$contacter['content'] .= $poll_comments;
				}
				/*handle poll end. 20100.16.*/
				
				$email_data['message'] = Mage::helper('contacter')->__("%s  has submitted a message for %s",$name_title.' '.$post['first_name'].' '.$post['last_name'],$email_data['comment']);
				$postObject = new Varien_Object();
                $postObject->setData($email_data);
                $postObject->setData( 'date',date( "Y-m-d H:i:s",time() ) );
				$reply_to = '';
				isset( $post['email'] ) && $reply_to = $post['email'];
//				$email = Mage::getStoreConfig('contacterconfiguration/general/email');
//				$mailTemplate = Mage::getModel('core/email_template');
//                /* @var $mailTemplate Mage_Core_Model_Email_Template */
//                                
//                $mailTemplate->setDesignConfig(array('area' => 'frontend'))
//                    ->setReplyTo($post['email'])
//                    ->sendTransactional(
//                        Mage::getStoreConfig('contacterconfiguration/general/email_template'),
//                        'sales',
//                        $email,
//                        null,
//                        array('data' => $postObject,'product_list' => nl2br( $product_list_content ) )
//                    );
				
				$model = Mage::getModel('contacter/contacter');		
				$model->setData($contacter);
				
              	$model->save();
				Mage::getSingleton('customer/session')->addSuccess(Mage::helper('contacter')->__('Your question was successfully saved'));				
				Mage::getSingleton( "contacter/contacter" )->clearProduct();
				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				
				if( $page == "referer" ){
					$this->_redirectReferer();
				}else{
					$this->_redirect('*/*/'.$page);
				}
                return;
            } catch (Exception $e) {
                $translate->setTranslateInline(true);

                Mage::getSingleton('customer/session')->addError(Mage::helper('contacts')->__('Unable to submit your request. Please, try again later'));
                $this->_redirect('*/*/');
                return;
            }

        } else {
            $this->_redirect('*/*/');
        }
    }
    
    public function saveProductList()
    {
    	$url_request = Mage::app()->getRequest();
    	$products = $url_request->getParams();
		$session = Mage::getSingleton('catalog/session');
    	$products_array = $session->getData( "products_make_offer" );
    	
    	if( isset( $products['offer_price'] ) ){
	    	if( !is_array( $products['offer_price'] ) ){
	    		$products['product_id'] = array( $products['product_id'] );
	    		$products['offer_qty'] = array( $products['offer_qty'] );
	    		$products['offer_price'] = array( $products['offer_price'] );
	    	}
	    	foreach( $products['product_id'] as $index => $product_id ){
	    		if( $products['offer_qty'][$index] > 0 && $products['offer_price'][$index] > 0 ){
	    			
	    			$products_array['product_id'][] = $product_id;
	    			$products_array['offer_qty'][] = $products['offer_qty'][$index];
	    			$products_array['offer_price'][] = $products['offer_price'][$index];
	    		}
	    	}
	    	
	    	
	    	$session->setData( "products_make_offer",$products_array );    	
    	}
    }
	
	public function ValidationAction()
	{
		
		$width = Mage::app()->getRequest()->getParam('width')?Mage::app()->getRequest()->getParam('width'):80;
		$height =  Mage::app()->getRequest()->getParam('height')?Mage::app()->getRequest()->getParam('height'):20;
		$type= Mage::app()->getRequest()->getParam('type')?Mage::app()->getRequest()->getParam('type'):'png';
		
		$rand	=	range(0,9);
		shuffle($rand);
		$verifyCode	=	array_slice($rand,0,4);		
        $letter = implode(" ",$verifyCode);        
		Mage::getModel('customer/session')->setData('verify_code',$verifyCode);
        $im = imagecreate($width,$height);
        $r = array(225,255,255,223);
        $g = array(225,236,237,255);
        $b = array(225,236,166,125);
        $key = mt_rand(0,3);
        $backColor = imagecolorallocate($im, $r[$key],$g[$key],$b[$key]); 
		$borderColor = imagecolorallocate($im, 100, 100, 100);                    //±ß¿òÉ«
        imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $backColor);
        imagerectangle($im, 0, 0, $width-1, $height-1, $borderColor);
        $numberColor = imagecolorallocate($im, 255,rand(0,100), rand(0,100));
        $stringColor = imagecolorallocate($im, rand(0,100), rand(0,100), 255);
		// Ìí¼Ó¸ÉÈÅ
		for($i=0;$i<10;$i++){
			$fontcolor=imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
			imagearc($im,mt_rand(-10,$width),mt_rand(-10,$height),mt_rand(30,300),mt_rand(20,200),55,44,$fontcolor);
		}
		for($i=0;$i<255;$i++){
			$fontcolor=imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
			imagesetpixel($im,mt_rand(0,$width),mt_rand(0,$height),$fontcolor);
		}
		imagestring($im, 5, 7, 4, $letter, $stringColor);
		
		header("Content-type: image/".$type);
        $ImageFun='Image'.$type;
        $ImageFun($im);
        imagedestroy($im);  
        
	}
}