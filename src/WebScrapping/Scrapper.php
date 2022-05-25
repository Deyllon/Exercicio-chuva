<?php

namespace Galoa\ExerciciosPhp2022\WebScrapping;
require_once 'vendor/autoload.php';

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use DOMXPath;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\CellAlignment;




/**
 * Does the scrapping of a webpage.
 */
class Scrapper {

  /**
   * Loads paper information from the HTML and creates a XLSX file.
   */
  public function scrap(\DOMDocument $dom): void {

    $xPath = new DOMXPath($dom);
    $classname = "paper-card";
    $domNodeList = $xPath -> query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
    
    /**
     * An array to create the xlsx file
     */
    $data = [["ID","Title", "Type", "Author 1", "Author 1 Institution", "Author 2", "Author 2 Institution",
    "Author 3", "Author 3 Institution", "Author 4", "Author 4 Institution", "Author 5", "Author 5 Institution", 
    "Author 6", "Author 6 Institution", "Author 7", "Author 7 Institution", "Author 8", "Author 8 Institution",
    "Author 9", "Author 9 Institution","Author 10", "Author 10 Institution", "Author 11", "Author 11 Institution",
    "Author 12", "Author 12 Institution",  "Author 13",  "Author 13 Institution",  "Author 14",  "Author 14 Institution",
    "Author 15",  "Author 15 Institution", "Author 16", "Author 16 Institution", "Author 17", "Author 17 Institution",
    "Author 18", "Author 18 Institution"]];

    /**
     *we take each article from the article list and iterate over it 
     *trying to get the title and the information of the authors and the type, 
     *after we get this information they will not be organized so we organize it 
     *so that the creation of the file is easier, 
     *we use a help list which will be added to the data list  and then 
     *this help list will be reset with each new iteration
     */
    foreach($domNodeList as $article){
      $elementsArticle = [];

      foreach($article -> childNodes as $elements){

        if($elements -> nodeName ==="h4"){
           $title =  $elements -> textContent;
           $elementsArticle[] = $title;
        }

        else{
          
          foreach($elements -> childNodes as $authorUniversityOrInstitute){
            
            if($authorUniversityOrInstitute-> nodeName ==="span"){
              $university =  $authorUniversityOrInstitute ->getAttribute("title");
              $author =  $authorUniversityOrInstitute -> textContent;
              array_push($elementsArticle,$author, $university);
            }

            else{
              $institute = $authorUniversityOrInstitute ->firstChild;
              if($institute != NULL && $institute != ""){
                $institute = $institute -> textContent;
                array_splice($elementsArticle, 1, 0, $institute);
              }

              $id = $authorUniversityOrInstitute ->lastChild;
              if($id != NULL && filter_var($id -> textContent, FILTER_SANITIZE_NUMBER_INT)){
                $id =  $id -> textContent;
                array_splice($elementsArticle, 0,0, $id);
              }

            }

          }
          
        }

      }

      unset($elementsArticle[2]);
      $elementsArticle = array_values($elementsArticle);
      array_push($data, $elementsArticle);
    }
    /**
     * here we create the styles and prepare the file to be written
     */
    $filePath = getcwd().'/exercices.xlsx';
    $writer = WriterEntityFactory::createXLSXWriter();
    $writer->openToFile($filePath);
    $style = (new StyleBuilder())     
           ->setCellAlignment(CellAlignment::LEFT)
           ->setFontSize(8)
           ->setShouldWrapText()
           ->build();
    
    /**
     * we iterate over the data list taking each element, 
     * this element will be a list then we will iterate through this list 
     * and we will add each element of it to a list that we will call a cell, 
     * after that this cell will be passed as a parameter to a method of creating lines
     */
    foreach ($data as $articledata) {
      $cells = [];

      for($i = 0; $i < count($articledata); $i++){
        $cells[] = WriterEntityFactory::createCell($articledata[$i]);
      }

      $singleRow = WriterEntityFactory::createRow($cells, $style);
      $writer->addRow($singleRow);

    }
      
    $writer->close();

  } 
}
