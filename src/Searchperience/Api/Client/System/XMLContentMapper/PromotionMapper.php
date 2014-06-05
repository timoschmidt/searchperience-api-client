<?php

namespace Searchperience\Api\Client\System\XMLContentMapper;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Document\Promotion;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class PromotionMapper extends AbstractMapper {

	/**
	 * @param Promotion $promotion
	 */
	public function toXML(Promotion $promotion) {
		$dom        = new \DOMDocument('1.0','UTF-8');
		$promotionNode  = $dom->createElement('promotion');

		$title = $dom->createElement('title',$promotion->getPromotionTitle());
		$promotionNode->appendChild($title);

		$type = $dom->createElement('type',$promotion->getPromotionType());
		$promotionNode->appendChild($type);

		$image = $dom->createElement('image',$promotion->getImageUrl());
		$promotionNode->appendChild($image);

		$searchTerms = $dom->createElement('searchterms');
		foreach($promotion->getKeywords() as $keyWord) {
			$keyWordNode = $dom->createElement('searchterm',$keyWord);
			$searchTerms->appendChild($keyWordNode);
		}
		$promotionNode->appendChild($searchTerms);

		$solrFieldValues = $dom->createElement('solrfieldvalues');

		foreach($promotion->getFieldValues() as $fieldName => $fieldValue) {
			$solrFieldValue = $dom->createElement('solrfieldvalue', $fieldValue);
			$fieldNameAttribute = $dom->createAttribute('fieldname');
			$attributeValue = $dom->createTextNode($fieldName);
			$fieldNameAttribute->appendChild($attributeValue);

			$solrFieldValue->appendChild($fieldNameAttribute);
			$solrFieldValues->appendChild($solrFieldValue);
		}

		$promotionNode->appendChild($solrFieldValues);
		$content = $dom->createElement('content');
		$cdata = $dom->createCDATASection($promotion->getPromotionContent());
		$content->appendChild($cdata);
		$promotionNode->appendChild($content);
		$dom->appendChild($promotionNode);

		return $dom->saveXML();

	}

	/**
	 * @param Promotion $promotion
	 * @param $contentXMLs
	 */
	public function fromXML(Promotion $promotion, $contentXML) {
		$contentDOM = new \DOMDocument('1.0', 'UTF-8');
		$contentDOM->loadXML($contentXML);

		$xpath = new \DOMXPath($contentDOM);
		$promotion->__setProperty('promotionTitle', $this->getFirstNodeContent($xpath,'//title'));
		$promotion->__setProperty('promotionType', $this->getFirstNodeContent($xpath,'//type'));
		$promotion->__setProperty('imageUrl', $this->getFirstNodeContent($xpath,'//image'));

		$searchTerms = $xpath->query("//searchterm");
		$keywords = array();

		foreach($searchTerms as $searchTerm) {
			$keywords[] = $searchTerm->textContent;
		}

		$promotion->__setProperty('keywords', $keywords);

		$solrFieldValues = $xpath->query("//solrfieldvalue");
		$fieldValues = array();

		foreach($solrFieldValues as $solrFieldValue) {
			/** @var $solrFieldValue \DOMElement */
			$fieldName = $solrFieldValue->getAttribute("fieldname");
			$fieldValues[$fieldName] = $solrFieldValue->textContent;
		}

		$promotion->__setProperty('fieldValues',$fieldValues);
		$promotion->__setProperty('promotionContent', $this->getFirstNodeContent($xpath,'//content') );
	}
}