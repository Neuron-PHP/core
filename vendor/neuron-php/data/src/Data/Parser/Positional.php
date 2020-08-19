<?php

namespace Neuron\Data\Parser;

class Positional implements IParser
{
   /**
    * @param $Text
    * @param array $Locations name, start, length
    * @return array
    */

   public function parse($Text, $Locations = array() )
   {
      $aResults = array();

      foreach($Locations as $aPos )
      {
         $aResults[ $aPos[ 'name' ] ] = trim( substr( $Text, $aPos[ 'start' ], $aPos[ 'length' ] ) );
      }

      return $aResults;
   }
}

