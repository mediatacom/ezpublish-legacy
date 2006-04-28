<?php
//
// Definition of eZDiffXMLTextEngine class
//
// <creation-tag>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file ezdiffxmltextengine.php
  eZDiffXMLTextEngine class
*/

/*!
  \class eZDiffXMLTextEngine ezdiffxmltextengine.php
  \ingroup eZDiff
  \brief This class creates a diff for xml text.
*/

include_once( 'lib/ezdiff/classes/ezdiffengine.php' );

class eZDiffXMLTextEngine extends eZDiffEngine
{
    function eZDiffXMLTextEngine()
    {
    }

    /*!
      This function calculates changes in xml text and creates an object to hold
      overview of changes.
    */
    function createDifferenceObject( $fromData, $toData )
    {
        include_once( 'lib/ezdiff/classes/ezxmltextdiff.php' );
        include_once( 'lib/ezdiff/classes/ezdifftextengine.php' );
        include_once( 'lib/ezutils/classes/ezini.php' );
        include_once( 'kernel/classes/datatypes/ezxmltext/handlers/input/ezsimplifiedxmleditoutput.php' );
        include_once( 'lib/ezxml/classes/ezxml.php' );

        $changes = new eZXMLTextDiff();
        $contentINI = eZINI::instance( 'content.ini' );
        $useSimplifiedXML = $contentINI->variable( 'ContentVersionDiffSettings', 'UseSimplifiedXML' );
        $diffSimplifiedXML = ( $useSimplifiedXML == 'enabled' );

        $oldXMLTextObject = $fromData->content();
        $newXMLTextObject = $toData->content();
        
        $oldXML = $oldXMLTextObject->attribute( 'xml_data' );
        $newXML = $newXMLTextObject->attribute( 'xml_data' );

        $simplifiedXML = new eZSimplifiedXMLEditOutput();
        $xml = new eZXML();
        $domOld =& $xml->domTree( $oldXML, array( 'CharsetConversion' => false, 'ConvertSpecialChars' => false ) );
        $domNew =& $xml->domTree( $newXML, array( 'CharsetConversion' => false, 'ConvertSpecialChars' => false ) );
        $old = $simplifiedXML->performOutput( $domOld );
        $new = $simplifiedXML->performOutput( $domNew );

        if ( !$diffSimplifiedXML )
        {
            $old = trim( strip_tags( $old ) );
            $new = trim( strip_tags( $new ) );

            $pattern = array( '/[ ][ ]+/',
                              '/ \n( \n)+/',
                              '/^ /m',
                              '/(\n){3,}/' );
            $replace = array( ' ',
                              "\n",
                              '',
                              "\n\n" );

            $old = preg_replace( $pattern, $replace, $old );
            $new = preg_replace( $pattern, $replace, $new );
        }

        $oldArray = split( "\n", $old );
        $newArray = split( "\n", $new );

        $oldSums = array();
        foreach( $oldArray as $paragraph )
        {
            $oldSums[] = md5( $paragraph );
        }

        $newSums = array();
        foreach( $newArray as $paragraph )
        {
            $newSums[] = md5( $paragraph );
        }

        $textDiffer = new eZDiffTextEngine();

        $pre = $textDiffer->preProcess( $oldSums, $newSums );
        $out = $textDiffer->createOutput( $pre, $oldArray, $newArray );
        $changes->setChanges( $out );
        return $changes;
    }
}

?>
