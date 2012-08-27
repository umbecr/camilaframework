<?php

/* This File is part of Camila PHP Framework
   Copyright (C) 2006-2011 Umberto Bresciani

   Camila PHP Framework is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   Camila PHP Framework is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with Camila PHP Framework; if not, write to the Free Software
   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA */


include CAMILA_LIB_DIR.'hawhaw/hawhaw.inc';

class CHAW_hdmlcardset extends HAW_hdmlcardset
{

};

class CHAW_deck extends HAW_deck
{
  var $camila_num_tables = 0;
  var $camila_headjsscripts = Array();
  var $camila_headjsscriptsids = Array();
  var $camila_num_headjsscripts = 0;
  var $camila_jsstrings = Array();
  var $camila_num_jsstrings = 0;
  var $camila_export_enabled = true;

  var $camila_pdf_export_enabled = true;
  var $camila_rtf_export_enabled = true;
  var $camila_csv_export_enabled = false;
  var $camila_xls_export_enabled = false;
  var $camila_xml2pdf_export_enabled = true;
  var $camila_print_export_enabled = true;

  var $camila_export_file_exist = false;

  var $camila_worktable = false;

  var $js_enabled;

  function CHAW_deck($title, $alignment, $output)
  {
      global $_CAMILA;

      if (!isset($_REQUEST['camila_print'])) {
    	  $this->camila_set_jsstring('CAMILA_IMG_DIR', CAMILA_IMG_DIR);
          $this->camila_set_jsstring('CAMILA_ERROR', $_CAMILA['error'] > 0 ? '1' : '0');
          $this->camila_set_jsstring('CAMILA_EXPORTING', $this->camila_exporting() ? '1' : '0');

          $this->camila_set_jsstring('expand', camila_get_translation('camila.clicktoexpand'));
          $this->camila_set_jsstring('collapse', camila_get_translation('camila.clicktocollapse'));
          $this->camila_set_jsstring('pleasewait', camila_get_translation('camila.pleasewait'));
          $this->camila_set_jsstring('clicktoedit', camila_get_translation('camila.clicktoedit'));

      	  $this->camila_add_js("<script type='text/javascript' src='".CAMILA_LIB_DIR."cross-browser.com/x/x_core.js'></script>\n");
          $this->camila_add_js("<script type='text/javascript' src='".CAMILA_LIB_DIR."cross-browser.com/x/x_event.js'></script>\n");
          $this->camila_add_js("<script type='text/javascript' src='".CAMILA_LIB_DIR."cross-browser.com/x/lib/xprevsib.js'></script>\n");
          $this->camila_add_js("<script type='text/javascript' src='".CAMILA_LIB_DIR."cross-browser.com/x/lib/xnextsib.js'></script>\n");
          $this->camila_add_js("<script type='text/javascript' src='".CAMILA_LIB_DIR."cross-browser.com/x/lib/xwalkul.js'></script>\n");
          $this->camila_add_js("<script type='text/javascript' src='".CAMILA_LIB_DIR."cross-browser.com/x/lib/xfirstchild.js'></script>\n");
          $this->camila_add_js("<script type='text/javascript' src='".CAMILA_LIB_DIR."cross-browser.com/x/lib/xaddclass.js'></script>\n");
          $this->camila_add_js("<script type='text/javascript' src='".CAMILA_LIB_DIR."cross-browser.com/x/lib/xremoveclass.js'></script>\n");
          $this->camila_add_js("<script type='text/javascript' src='".CAMILA_LIB_DIR."cross-browser.com/x/lib/xtableiterate.js'></script>\n");
          $this->camila_add_js("<script type='text/javascript' src='".CAMILA_LIB_DIR."cross-browser.com/x/lib/xhasclass.js'></script>\n");
          $this->camila_add_js("<script type='text/javascript' src='".CAMILA_LIB_DIR."cross-browser.com/x/lib/xparent.js'></script>\n");
          $this->camila_add_js("<script type='text/javascript' src='".CAMILA_LIB_DIR."cross-browser.com/x/lib/xdisplay.js'></script>\n");
          $this->camila_add_js("<script type='text/javascript' src='".CAMILA_LIB_DIR."cross-browser.com/x/lib/xappendchild.js'></script>\n");
          $this->camila_add_js("<script type='text/javascript' src='".CAMILA_LIB_DIR."cross-browser.com/x/lib/xdocsize.js'></script>\n");
          $this->camila_add_js("<script type='text/javascript' src='".CAMILA_LIB_DIR."cross-browser.com/x/lib/xgetcookie.js'></script>\n");
          $this->camila_add_js("<script type='text/javascript' src='".CAMILA_LIB_DIR."cross-browser.com/x/lib/xsetcookie.js'></script>\n");
          $this->camila_add_js("<script type='text/javascript' src='".CAMILA_LIB_DIR."cross-browser.com/x/lib/xoffsettop.js'></script>\n");

          $this->camila_add_js("<script type='text/javascript' src='".CAMILA_LIB_DIR."cross-browser.com/x/lib/xslideto.js'></script>\n");

          $this->camila_add_js("<script type='text/javascript' src='".CAMILA_LIB_DIR."cross-browser.com/x/lib/xwindow.js'></script>\n");

          $this->camila_add_js("<script type=\"text/javascript\" src=\"".CAMILA_DIR."js/camila.js\"></script>\n");
          $this->camila_add_js("<script type=\"text/javascript\" src=\"".CAMILA_DIR."js/camila_instantedit.js\"></script>\n");
          $this->camila_add_js("<script type=\"text/javascript\" src=\"".CAMILA_DIR."js/camila_security.js\"></script>\n");

          $this->camila_add_js("<script type=\"text/javascript\">\n");
          $this->camila_add_js("  camila_addDOMLoadEvent ( function()\n");

          $json = new Services_JSON();
    
          if ($_SERVER['PHP_SELF'] == CAMILA_LOGIN_HOME && $_CAMILA['user_loggedin']==1 && CAMILA_SPLASH_IMG != '')
              $this->camila_add_js("  {camila_init('" . str_replace("'", "\'", $json->encode($this->camila_jsstrings)) . "',true);\n");
          else
              $this->camila_add_js("  {camila_init('" . str_replace("'", "\'", $json->encode($this->camila_jsstrings)) . "');\n");
          $this->camila_add_js("} )");
          $this->camila_add_js("</script>\n");

      }

      if (file_exists($this->camila_export_get_dir() . $this->camila_export_safe_filename() . '.' . $this->camila_export_get_ext()))
          $this->camila_export_file_exists = true;
          
      $this->{get_parent_class(__CLASS__)}($title, $alignment, $output);
}

  function camila_export_get_dir() {
      global $_CAMILA;
      if (CAMILA_FM_EXTFS_ENABLED)
          return CAMILA_FM_ROOTDIR . '/' . $_CAMILA['adm_user_group'] . '/';
      else
          return CAMILA_FM_ROOTDIR . $_REQUEST['camila_export_action'] . '/';
  }

  function camila_export_get_ext() {
      if(isset($_REQUEST['camila_xls']))
          return 'xls';

      if(isset($_REQUEST['camila_csv']))
          return 'csv';

      if(isset($_REQUEST['camila_pdf']))
          return 'pdf';

      if(isset($_REQUEST['camila_rtf']))
          return 'rtf';

      if(isset($_REQUEST['camila_xml2pdf']))
          return 'pdf';

  }

  function camila_export_filename() {
      global $_CAMILA;
      $filename = $this->camila_export_safe_filename().'.'.$this->camila_export_get_ext();
      if (CAMILA_FM_EXTFS_ENABLED)
          $filename = $filename.camila_hash(CAMILA_FM_PREFIX);
      $_CAMILA['camila_export_last_filename'] = $filename;
      return $filename;
  }

  function camila_export_safe_filename() {
      global $_CAMILA;
      $filename = trim($_REQUEST['camila_export_filename']);
      return $filename;
  }

  function camila_export_suggested_filename() {
      global $_CAMILA;
      $filename = trim($_CAMILA['page_full_title']) . ' ' . $_CAMILA['db']->UserDate(date('Y-m-d'), camila_get_locale_date_adodb_format());
      $filename = str_replace("/", "-", $filename);
      return $filename;
  }

  function camila_export_download_link() {
      global $_CAMILA;
      if (CAMILA_FM_AJAXPLORER_ENABLED)
          $fname = $_REQUEST['camila_export_action'] . '/' . $_CAMILA['page']->camila_export_safe_filename().'.'.$_CAMILA['page']->camila_export_get_ext();
      elseif (CAMILA_FM_EXTFS_ENABLED)
          $fname = $_CAMILA['camila_export_last_filename'];
      else
          $fname = $_CAMILA['page']->camila_export_safe_filename().'.'.$_CAMILA['page']->camila_export_get_ext();

      if (CAMILA_FM_AJAXPLORER_ENABLED)
          $url = 'cf_ajaxplorer_content.php?action=download&file=' . urlencode($fname);
      else
          $url = 'cf_docs.php?camila_download=' . urlencode($fname);
      return $url;
  }

  function camila_exporting()
  {
    if (isset($_REQUEST["camila_inline"]) || isset($_REQUEST["camila_print"]) || isset($_REQUEST["camila_soap"]) || isset($_REQUEST["camila_js"]) || isset($_REQUEST["camila_txt"]) || isset($_REQUEST["camila_pdf"]) || isset($_REQUEST["camila_xls"]) || isset($_REQUEST["camila_csv"]) || isset($_REQUEST["camila_rtf"]) || isset($_REQUEST["camila_xml2pdf"]) || isset($_REQUEST["camila_bookmark"]) || isset($_REQUEST["camila_json"]) || isset($_REQUEST["camila_xml"]))
      return true;
    else
      return false;
  }

  // Add js block in head section
  // If $id is different than '' $code is added once
  function camila_add_js($code, $id='')
  {
      if ($id=='' || !in_array($id, $this->camila_headjsscriptsids)) {
          $this->camila_headjsscripts[$this->camila_num_headjsscripts] = $code;
          $this->camila_num_headjsscripts++;
          $this->camila_headjsscriptsids[]=$id;
      }
  }


  function camila_set_jsstring($name,$val)
  {
      $this->camila_jsstrings[$name] = $val;
      $this->camila_num_jsstrings++;
  }

  function camila_collapsible_start($id,$expand=true,$title='')
  {
	if ($expand)
	  $code = "<div class='camilacollapsibleon'>";
	else
	  $code = "<div class='camilacollapsibleoff'>";

	$code.=$title."<div id='camilacollapsible_".$id."'>";
    $js = new CHAW_js($code);
    $this->add_userdefined($js);
    
  }

  function camila_collapsible_end()
  {
    $code = "</div></div>";
    $js = new CHAW_js($code);
    $this->add_userdefined($js);
  }
              
  function add_table($table)
  {
     $this->camila_num_tables++;
     parent::add_table($table);
  }


  function create_page()
  {
    global $haw_license_holder;
    global $haw_license_domain;
    global $haw_license_key;
    global $haw_signature;
    global $haw_sig_text;
    global $haw_sig_link;

    // add hawoutput query parameter to redirection url, if required
    if ($this->red_url)
      HAW_handle_forced_output($this->red_url, $this->hawoutput);

    if ($this->debug)
      header("content-type: text/plain");

    if ($this->disable_cache)
      header("cache-control: no-cache");

    if ($this->ml == HAW_HTML)
    {
      // create HTML page header

      if (!$this->debug)
      {
        if ($this->xhtml)
          $ct = "content-type: " . $this->xhtmlmp_preferred_mime_type;
        else
          $ct = sprintf("content-type: text/html;charset=%s", $this->charset);

        header($ct);
      }

      if ($this->xhtml)
      {
        // advice transcoding proxies (http://dev.mobi/node/611)
        header("cache-control: no-transform");
        header("vary: User-Agent, Accept");

        printf("<?xml version=\"1.0\" encoding=\"%s\"?>\n", $this->charset);

        if ($this->lynx)
          echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\" >\n";
        else
          echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\" \"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\" >\n";
      }
      elseif ($this->css_enabled)
        echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\n";

      if ($this->language)
      {
        if ($this->xhtml)
          $language = sprintf(" xml:lang=\"%s\"", $this->language);
        else
          $language = sprintf(" lang=\"%s\"", $this->language);
      }
      else
        $language = "";

      if ($this->xhtml)
        // W3C mobileOK requires namespace attribute
        $namespace = " xmlns=\"http://www.w3.org/1999/xhtml\"";
      else
        $namespace = "";
        
      printf("<html%s%s>\n", $language, $namespace);
      echo "<head>\n";

      // validation issue: HTML does not allow XHTML-stylish "/>" within <head> part
      // HTML has set option SHORTTAG=YES in SGML declaration
      // ==> "/" closes the tag and ">" will be treated as text
      // <body> allows text, but <head> does not! 
      if ($this->xhtml)
        $endtag = "/>";
      else
        $endtag = ">";

      if (!$this->iModestyle && !$this->MMLstyle)
      {
        // cHTML and MML don't support meta tags

        if ($haw_license_domain)
          $license = " - registered for $haw_license_domain";
        else
          $license = "";

        printf("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=%s\" %s\n",
                $this->charset, $endtag);
        printf("<meta name=\"GENERATOR\" content=\"%s (PHP) %s%s\" %s\n",
               HAW_VERSION, HAW_COPYRIGHT, $license, $endtag);

        if ($this->desktopBrowser && isset($haw_license_key))
        {
          printf("<meta name=\"LICENSE_KEY\" content=\"%s\" %s\n",
                 $haw_license_key, $endtag);
        }
        
        if ($this->timeout > 0)
        {
          printf("<meta http-equiv=\"refresh\" content=\"%d; URL=%s\" %s\n",
                  $this->timeout, HAW_specchar($this->red_url, $this), $endtag);
        }

        if ($this->disable_cache)
        {
          echo "<meta http-equiv=\"Cache-Control\" content=\"must-revalidate\" $endtag\n";
          echo "<meta http-equiv=\"Cache-Control\" content=\"no-cache\" $endtag\n";
          echo "<meta http-equiv=\"Cache-Control\" content=\"max-age=0\" $endtag\n";
          echo "<meta http-equiv=\"Expires\" content=\"0\" $endtag\n";
        }

        //Camila Framework - New Block BEGIN
        
        for ($i=0; $i<$this->camila_num_headjsscripts; $i++)
        {
          global $_CAMILA;
          if ($_CAMILA['output'] == HAW_OUTPUT_AUTOMATIC)
	      echo $this->camila_headjsscripts[$i];
        }
        
        //Camila Framework - New Block END

      }

      if ($this->PDAstyle)
      {
        echo "<meta name=\"HandheldFriendly\" content=\"True\" $endtag\n";
      }

      if ($this->css_enabled && $this->css)
      {
        printf("<link href=\"%s\" type=\"text/css\" rel=\"stylesheet\" />\n", $this->css);
      }

      // init style properties
      $bgcolor = "";
      $background = "";
      $disp_background = "";
      $size = "";
      $color = "";
      $link_color = "";
      $vlink_color = "";
      $face = "";
      //Camila Framework: text-align changed
      $bodystyle = "text-align: left; ";
      $css_style = "";

      if ($this->desktopBrowser || $this->PDAstyle || $this->xhtml)
      {
        if ($this->desktopBrowser)
        {
          // big-screen browser
 
          if ($this->bgcolor)
          {
            // set background color (=window background color) 
            $bgcolor = " bgcolor=\"" . $this->bgcolor . "\"";
            $bodystyle .= sprintf("background-color:%s; ", $this->bgcolor);
          }

          if ($this->background)
          {
            // set wallpaper (=window wallpaper)
            $background = " background=\"" . $this->background . "\"";
            $bodystyle .= sprintf("background-image:url(%s); ", $this->background);
          }

          if (!$this->css) // settings in css-file have priority
          {
            // set display defaults, if not assigned by yet
            if (!$this->disp_bgcolor)
              $this->disp_bgcolor = HAW_DISP_BGCOLOR;
            if (!$this->link_color)
              $this->link_color = HAW_DISP_LINKCOLOR;
            if (!$this->vlink_color)
              $this->vlink_color = HAW_DISP_VLINKCOLOR;
            if (!$this->face)
              $this->face = HAW_DISP_FACE;
          }
        }
        else
        {
          // XHTML or PDA

          if ($this->disp_bgcolor)
          {
            // set background color of mobile device
            $bgcolor = " bgcolor=\"" . $this->disp_bgcolor . "\"";
            $bodystyle .= sprintf("background-color:%s; ", $this->disp_bgcolor);
          }

          if ($this->disp_background)
          {
            // set wallpaper of mobile device
            $background = " background=\"" . $this->disp_background . "\"";
            $bodystyle .= sprintf("background-image:url(%s); ", $this->disp_background);
          }
        }

        if ($this->size)
        {
          // set the font size for all characters in a HTML created page
          $size = " size=\"" . $this->size . "\"";
          $bodystyle .= sprintf("font-size:%s; ", $this->size);
        }

        if ($this->color)
        {
          // set the color for all characters in a HTML created page
          $color = " color=\"" . $this->color . "\"";
          $bodystyle .= sprintf("color:%s; ", $this->color);
        }

        if ($this->link_color)
        {
          // set the color of links in a HTML created page
          $link_color = " link=\"" . $this->link_color . "\"";
          $css_style .= sprintf("a:link { color:%s; }\n", $this->link_color);
        }

        if ($this->vlink_color)
        {
          // set the color of visited links in a HTML created page
          $vlink_color = " vlink=\"" . $this->vlink_color . "\"";
          $css_style .= sprintf("a:visited { color:%s; }\n", $this->vlink_color);
        }

        if ($this->face)
        {
          // set the font for all characters in a HTML created page
          $face = " face=\"" . $this->face . "\"";
          $bodystyle .= sprintf("font-family:%s; ", $this->face);
        }

        $this->fontstyle_attrbs = $size . $color . $face;
      }

      printf("<title>%s</title>\n", HAW_specchar($this->title, $this));

      if ($this->desktopBrowser && ($this->use_simulator == HAW_SIM_SKIN) && $this->css_enabled)
        // use HAWHAW default- or user-defined skin
        printf("<link rel=\"stylesheet\" type=\"text/css\" href=\"%s\" $endtag\n", $this->skin);

      //Camila Framework: Added

      if ($p_file = fopen(CAMILA_CSS_DIR.'print.css', "r")) {          
          while (!feof($p_file))
              $css_style.= fgets($p_file);
          
          fclose($p_file);
          
      }

      if ($this->css_enabled) {
        printf("<style type=\"text/css\">\n<!--\nbody { %s}\n%s-->\n</style>\n", $bodystyle, $css_style);

        //Camila Framework: Added
      global $_CAMILA;
      if ($_CAMILA['user_preferences']['c_tf'] == '')
        $_CAMILA['user_preferences']['c_tf'] = CAMILA_TABLE_FACE;

      if ($_CAMILA['user_preferences']['c_ts'] == '')
        $_CAMILA['user_preferences']['c_ts'] = CAMILA_TABLE_SIZE;

        if (($_CAMILA['user_preferences']['c_ts'] != '') && ($_CAMILA['user_preferences']['c_tf'] != '')) {
            $tablestyle = sprintf("font-family:%s; ", $_CAMILA['user_preferences']['c_tf']);
            $tablestyle .= sprintf("font-size:%s; ", $_CAMILA['user_preferences']['c_ts']);

            printf("<style type=\"text/css\">\n<!--\ntable { %s}\n-->\n</style>\n", $tablestyle);
        }


      }

      echo "</head>\n";

      if ($this->css_enabled)
        echo "<body>\n";
      else
        printf("<body%s%s%s%s>\n", $bgcolor, $background, $link_color, $vlink_color);

        //Camila Framework: new block START

        if (!$this->camila_exporting() && !isset($_REQUEST['camila_popup']) && intval($_CAMILA['error'])==0) {
            $myPreferences = new CHAW_preferences();
            $myPreferences->create($this);
        }
        //Camila Framework: new block END


      if ($this->display_banners)
      {
        if ($this->number_of_top_banners > 0)
        {
          echo "<center>\n";

            for ($i=0; $i<$this->number_of_top_banners; $i++)
            {
              // display banners at the top of the HTML page
              $banner = $this->top_banners[$i];
              $banner->create();
            }

          echo "</center>\n";
        }
      }

      if ($this->desktopBrowser)
      {
        if ($this->css_enabled && ($this->use_simulator == HAW_SIM_SKIN))
        {

          //Camila Framework: New block START
          echo "<div id=\"camilamenutop\"></div>";
          //Camila Framework: New block END


          //Camila Framework: New block START
          echo "<div id=\"camilamenuleft\"></div>";
          //Camila Framework: New block END

          // use skin design
          echo "<div id=\"skin\">\n";

          if ($this->css_class)
            $class_param = " class=\"" . $this->css_class . "\"";
          else
            $class_param = "";
          
          printf("<div id=\"display\"%s>\n", $class_param);
        }
        else if ($this->use_simulator == HAW_SIM_CLASSIC)
        {
          // use classic HAWHAW design
          printf("<div id=\"classic\" style=\"background-color: %s; background-image: url(%s); border: %dpx solid #aaa; padding: 8px; width: %s; height: %s; margin: 0px auto; overflow: auto;\">\n",
                 $this->disp_bgcolor, $this->disp_background, $this->border, $this->width, $this->height);
        }
      }

      if (!$this->css_enabled && $this->fontstyle_attrbs)
      {
        // write style attributes, if any
        printf("<font%s>\n", $this->fontstyle_attrbs);
      }
    }
    else
    {
      // determine default values for WML, HDML and VXML form elements

      while (list($e_key, $e_val) = each($this->element))
      {
        if ($e_val->get_elementtype() == HAW_FORM)
        {
          // one (and only one!) form exists

          $form = $e_val;
          $defaults = $form->get_defaults();
        }
      }

      if ($this->ml == HAW_WML)
      {
        // create WML page header
        if (!$this->debug)
        {
          $ct = sprintf("content-type: text/vnd.wap.wml");
          header($ct);
        }

        if ($this->disable_cache)
        {
          // not all WAP clients interprete meta directives!
          // disable caching by sending HTTP content-location header with unique value

          if (isset($_SERVER['REQUEST_URI']))
            $request_uri = $_SERVER['REQUEST_URI'];
          else
            $request_uri = "";

          if (strchr($request_uri, "?"))
            // request URI already contains parameter(s)
            $header= sprintf("content-location: %s&hawcid=%s", $request_uri, date("U"));
          else
            // no parameters in URI
            $header= sprintf("content-location: %s?hawcid=%s", $request_uri, date("U"));

          header($header);
        }

        echo "<?xml version=\"1.0\"?" . ">\n";

        if ($this->owgui_1_3)
          echo "<!DOCTYPE wml PUBLIC \"-//OPENWAVE.COM//DTD WML 1.3//EN\" \"http://www.openwave.COM/dtd/wml13.dtd\" >\n";
        else
          echo "<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/DTD/wml_1.1.xml\">\n";

        printf("<!-- Generated by %s %s -->\n", HAW_VERSION, HAW_COPYRIGHT);

        if ($this->language)
          $language = sprintf(" xml:lang=\"%s\"", $this->language);
        else
          $language = "";

        printf("<wml%s>\n", $language);

        if ($this->disable_cache)
        {
          echo "<head>\n";
          echo "<meta http-equiv=\"Cache-Control\" content=\"must-revalidate\" forua=\"true\"/>\n";
          echo "<meta http-equiv=\"Cache-Control\" content=\"no-cache\" forua=\"true\"/>\n";
          echo "<meta http-equiv=\"Cache-Control\" content=\"max-age=0\" forua=\"true\"/>\n";
          echo "<meta http-equiv=\"Expires\" content=\"0\" forua=\"true\"/>\n";
          echo "<meta http-equiv=\"Pragma\" content=\"no-cache\" forua=\"true\"/>\n";
          echo "</head>\n";
        }

        if ($this->title)
          $title = " title=\"" . HAW_specchar($this->title,$this) . "\"";
        else
          $title = "";

        printf("<card%s>\n", $title);

        if (isset ($defaults) && $defaults)
        {
          // default values exist

          // set variables each time the card is enter in forward direction ...

          echo "<onevent type=\"onenterforward\">\n";
          echo "<refresh>\n";

          // initialize all WML variables with their default values
          while (list($d_key, $d_val) = each($defaults))
            printf("<setvar name=\"%s\" value=\"%s\"/>\n",
                   $d_val['name'], HAW_specchar($d_val['value'], $this));

          reset($defaults);

          echo "</refresh>\n";
          echo "</onevent>\n";

          // ... and backward direction

          echo "<onevent type=\"onenterbackward\">\n";
          echo "<refresh>\n";

          while (list($d_key, $d_val) = each($defaults))
            printf("<setvar name=\"%s\" value=\"%s\"/>\n", $d_val['name'], $d_val['value']);

          echo "</refresh>\n";
          echo "</onevent>\n";
        }

        // set redirection timeout
        if ($this->timeout > 0)
        {
           echo "<onevent type=\"ontimer\">\n";
           printf("<go href=\"%s\"/>\n", HAW_specchar($this->red_url, $this));
           echo "</onevent>\n";
           printf("<timer value=\"%d\"/>\n", $this->timeout*10);
        }

        // define <back> softkey
        echo "<do type=\"prev\" label=\"Back\">\n";
        echo "<prev/>\n";
        echo "</do>\n";
      }
      elseif ($this->ml == HAW_HDML)
      {
        // create HDML card set structure

        if (!isset($defaults))
          $defaults = array();

        $this->hdmlcardset = new HAW_hdmlcardset(HAW_specchar($this->title, $this),
                                                 $defaults, $this->disable_cache,
                                                 $this->debug, $this->charset);
      }
      elseif ($this->ml == HAW_VXML)
      {
        // create VXML page header
        if (!$this->debug)
        {
          $ct = sprintf("content-type: application/vxml+xml");
          header($ct);
        }

        printf("<?xml version=\"1.0\" encoding=\"%s\"?>\n", $this->charset);

        printf("<!-- Generated by %s %s -->\n", HAW_VERSION, HAW_COPYRIGHT);

        // language declaration breaks Voxeo Motorola voice browser!!!
        if ($this->language && !$this->MotorolaVoiceXML)
          $language = sprintf(" xml:lang=\"%s\"", $this->language);
        else
          $language = "";

        printf("<vxml%s version=\"2.0\">\n", $language);

        if ($this->disable_cache)
          echo "<meta http-equiv=\"Expires\" content=\"0\"/>\n";

        // define voice deck properties
        while (list($key, $val) = each($this->voice_property))
          printf("<property name=\"%s\" value=\"%s\"/>\n", $val["name"], $val["value"]);

        // Voxeo-specific TTS settings
        if ($this->MotorolaVoiceXML)
        {
          if ($this->language == "fr")
            echo "<property name=\"nuance.core.tts.ResourceName\" value=\"fr-FR.default\"/>\n";
          if ($this->language == "es")
            echo "<property name=\"nuance.core.tts.ResourceName\" value=\"es-MX.default\"/>\n";
        }

        echo "<form>\n";

        if (count($this->voice_navigator) > 0)
        {
          // allow user navigation in dedicated prompts

          echo "<var name=\"nav_counter\"/>\n";

          if (strlen($this->voice_navigator["repeat_dtmf"]) > 0)
            $dtmf = sprintf(" dtmf=\"%s\"", $this->voice_navigator["repeat_dtmf"]);
          else
            $dtmf = "";

          if ($this->voice_navigator["repeat_voice"])
            $voice_grammar = sprintf("<grammar>[%s]</grammar>", $this->voice_navigator["repeat_voice"]);
          else
            $voice_grammar = "";

          printf("<link%s event=\"repeat\">%s</link>\n", $dtmf, $voice_grammar); // define repeat link

          if (strlen($this->voice_navigator["forward_dtmf"]) > 0)
            $dtmf = sprintf(" dtmf=\"%s\"", $this->voice_navigator["forward_dtmf"]);
          else
            $dtmf = "";

          if ($this->voice_navigator["forward_voice"])
            $voice_grammar = sprintf("<grammar>[%s]</grammar>", $this->voice_navigator["forward_voice"]);
          else
            $voice_grammar = "";

          printf("<link%s event=\"forward\">%s</link>\n", $dtmf, $voice_grammar); // define forward link

          // define catch handler for links
          echo "<catch event=\"repeat\"><goto expritem=\"'block' + nav_counter\"/></catch>\n";
          echo "<catch event=\"forward\"><goto expritem=\"'block' + nav_counter + 'end'\"/></catch>\n";
        }

        if ($this->voice_text || $this->voice_audio_src)
        {
          // create introducing audio output for VoiceXML deck

          echo "<block><prompt>";

          HAW_voice_audio(HAW_specchar($this->voice_text, $this),
                        $this->voice_audio_src, HAW_VOICE_PAUSE, $this);

          echo "</prompt></block>\n";
        }
      }
    }

    // text-align aligns left, centered or right
    $divstyle = sprintf("text-align:%s;", $this->alignment);

    // position:relative is needed to work-around MSIE's Peekaboo bug ...
    if (isset($_SERVER['HTTP_USER_AGENT']) && strstr($_SERVER['HTTP_USER_AGENT'], " MSIE "))
      $divstyle .= " position:relative;";

    if ($this->ml == HAW_HTML)
    {
      if ($this->css_enabled)
      {
        if ($this->css_class)
        {
          $class = $this->css_class;

          if (isset($_SERVER['HTTP_USER_AGENT']) && strstr($_SERVER['HTTP_USER_AGENT'], " MSIE "))
            $class .= " peekaboo"; // CSS hook for peekaboo handling
        }
        else
        {
          if (isset($_SERVER['HTTP_USER_AGENT']) && strstr($_SERVER['HTTP_USER_AGENT'], " MSIE "))
            $class = "peekaboo";
          else
            $class = "";
        }
        
        if ($class)
          $class_param = " class=\"" . $class . "\"";
        else
          $class_param = "";
          
        printf("<div id=\"canvas\" style=\"%s\"%s>\n", $divstyle, $class_param);
        echo "<span id=\"hawcsshook1\"></span>\n"; // universal CSS hook for toolbars etc.
      }
      else
        printf("<div id=\"canvas\" align=\"%s\">\n", $this->alignment);
    }
    elseif ($this->ml == HAW_WML)
      printf("<p align=\"%s\">\n", $this->alignment);

    $i = 0;
    while (isset($this->element[$i]))
    {
      $page_element = $this->element[$i];
      switch ($page_element->get_elementtype())
      {
        case HAW_PLAINTEXT:
        case HAW_IMAGE:
        case HAW_TABLE:
        case HAW_FORM:
        case HAW_LINK:
        case HAW_PHONE:
        case HAW_LINKSET:
        case HAW_RAW:
        case HAW_PLUGIN:
        case HAW_RULE:
        case HAW_VOICERECORDER:
        {
          $element = $this->element[$i];
          $element->create($this);

          break;
        }
      }

      $i++;
    }

    if ($this->ml == HAW_HTML)
    {
      // create HTML page end

      //  ATTENTION!
      //
      //  DO NOT REMOVE THE COPYRIGHT LINK WITHOUT PERMISSION!
      //  IF YOU DO SO, YOU ARE VIOLATING THE LICENSE TERMS
      //  OF THIS SOFTWARE! YOU HAVE TO PAY NOTHING FOR THIS
      //  SOFTWARE, SO PLEASE BE SO FAIR TO ACCEPT THE RULES.
      // 
      //  PLEASE REFER TO THE LIBRARY HEADER AND THE HAWHAW
      //  HOMEPAGE FOR MORE INFORMATION:
      //  http://www.hawhaw.de/#license

      echo "</div>\n";

      if (!$this->css_enabled && $this->fontstyle_attrbs)
        echo "</font>\n";

      if ($this->desktopBrowser)
      {

        //Camila Framework:Moved down
        //if ($this->css_enabled && ($this->use_simulator == HAW_SIM_SKIN))
        //{
        //  // terminate display divs
        //  echo "</div>\n";
        //  echo "</div>\n";
        //}
        
        if ($this->use_simulator == HAW_SIM_CLASSIC)
          echo "</div>\n"; // terminate classic div

        if (!$this->lynx)
        {
          $signature = "";
          $default_text = "Powered by HAWHAW (C)";
          if ($haw_license_holder && $haw_signature)
          {
            if ($haw_signature == 1)
              $signature = "<small>" . $default_text . "</small>\n";
            else
              if ($haw_sig_text)
                if ($haw_sig_link)
                  $signature = sprintf("<a href=\"%s\" target=\"_blank\"><small>%s</small></a>\n", $haw_sig_link, $haw_sig_text);
                else
                  $signature = sprintf("<small>%s</small>\n", $haw_sig_text);
          }
          else
            $signature = sprintf("<a href=\"http://info.hawhaw.de/index.htm?host=%s\" target=\"_blank\"><small>%s</small></a>\n",
                                 $this->waphome, $default_text);

          if ($signature && ($this->use_simulator == HAW_SIM_NONE))
            printf("<br /><span style=\"font-size:8pt;\">%s</span>\n", $signature);
          else
          //Camila Framework: Add
          {
             if (!$this->camila_exporting() && !isset($_REQUEST['camila_popup']) && substr($_SERVER['HTTP_HOST'],0,9) !='localhost' && substr($_SERVER['HTTP_HOST'],0,9)!='127.0.0.1' && !CAMILA_PRIVATE_SERVER && !(CAMILA_WORKTABLE_HIDE_POWERED_BY_WHEN_LOGGED_IN && $_CAMILA['user_loggedin']==1))

            echo '<div class="camilapoweredby">'.$signature.'<small> and <a href="http://www.camilaframework.com">Camila Framework</a></small></div>';
          }
        }

        //Camila Framework:Moved down (see above)
        if ($this->css_enabled && ($this->use_simulator == HAW_SIM_SKIN))
        {
          // terminate display divs
          echo "</div>\n";
          echo "</div>\n";
        }

      }

      if ($this->display_banners)
      {
        if ($this->number_of_bottom_banners > 0)
        {
          echo "<center>\n";

            for ($i=0; $i<$this->number_of_bottom_banners; $i++)
            {
              // display banners at the bottom of the HTML page
              $banner = $this->bottom_banners[$i];
              $banner->create();
            }

          echo "</center>\n";
        }
      }

      echo "</body>\n";
      echo "</html>\n";
    }
    elseif ($this->ml == HAW_WML)
    {
      // create WML page end
      echo "</p>\n";
      echo "</card>\n";
      echo "</wml>\n";
    }
    elseif ($this->ml == HAW_HDML)
    {
      // create HDML page from hdml card set structure
      $cardset = $this->hdmlcardset;
      $cardset->create_hdmldeck();
    }
    elseif ($this->ml == HAW_VXML)
    {
      // create VoiceXML page end

      // set redirection timeout
      if ($this->red_url && ($this->timeout == 0))
      {
        // special handling for timeout value 0 in VoiceXML:
        // go immediately to next page after all text has been spoken
        // should be used for pure voice apps only!!!
        printf("<block><goto next=\"%s\" /></block>\n", HAW_specchar($this->red_url, $this));
      }
      elseif ($this->timeout > 0)
      {
        // redirect after <timout> to another URL
        // (define dummy grammar in case that no other grammar is active)
        printf("<field><grammar>[(hawhaw really rocks)]</grammar><prompt timeout=\"%ds\"/><noinput><goto next=\"%s\"/></noinput><nomatch /></field>\n",
               $this->timeout, HAW_specchar($this->red_url, $this));
      }
      elseif($this->voice_timeout > 0)
      {
        // there is at least one voice link active
        // wait longest link timeout value until disconnect is forced

        printf("<field><prompt timeout=\"%ds\"/>", $this->voice_timeout);

        // test for user-defined noinput event handler
        if (!isset($this->voice_noinput) || (count($this->voice_noinput) == 0))
          echo "<noinput><exit/></noinput>"; // terminate in case of no input

        echo "</field>\n";
      }

      echo "</form>\n";

      if ($this->voice_links)
        echo $this->voice_links;

      // create event handlers
      HAW_voice_eventhandler("help",    $this->voice_help,    $this);
      HAW_voice_eventhandler("noinput", $this->voice_noinput, $this);
      HAW_voice_eventhandler("nomatch", $this->voice_nomatch, $this);

      echo "</vxml>\n";
    }
  }
  
}

class CHAW_file
{
  var $name;
  var $label;
  var $size;
  var $maxlength;
  var $type;
  var $br;

  function CHAW_file($name, $label, $size="", $maxfilesize="")
  {
    $this->name = $name;
    $this->label = $label;
    $this->maxfilesize = $maxfilesize;
    $this->br = 1;
  }

  /**
    Set size of the input field. <br>
    Note: Will be ignored in case of HDML/VoiceXML output.
    @param size Number of characters fitting into the input field.
  */
  function set_size($size)
  {
    $this->size = $size;
  }

  /**
    Set maximum of allowed characters in the input field. <br>
    Note: Will be ignored in case of HDML output.
    @param maxlength Maximum number of characters the user can enter.
  */
  function set_maxlength($maxlength)
  {
    $this->maxlength = $maxlength;
  }


  function set_br($br)
  {
    if (!is_int($br) || ($br < 0))
      die("invalid argument in set_br()");

    $this->br = $br;
  }

  function get_name()
  {
    return $this->name;
  }

  function get_label()
  {
    return $this->label;
  }

  function get_size()
  {
    return $this->size;
  }

  function get_elementtype()
  {
    return HAW_USERDEFINED;
  }

  function create(&$deck)
  {
    $type = "type=\"file\"";

    if ($this->size)
      $size = sprintf("size=\"%d\"", $this->size);
    else
      $size = "";

    if ($deck->ml == HAW_HTML)
    {      
      // create HTML input
      if ($deck->xhtml || $deck->pureHTML)
      {
        if ($this->maxfilesize!="")
        printf("<input type=\"hidden\" name=\"%s\" value=\"%s\" />\n",
              "MAX_FILE_SIZE", $this->maxfilesize);
        printf("<label for=\"%s\">%s</label>\n",
                $this->name, HAW_specchar($this->label, $deck));
        printf("<input %s name=\"%s\" id=\"%s\" %s /> ",
                $type, $this->name, $this->name, $size);
        // create required amount of carriage return's
        for ($i=0; $i < $this->br; $i++)
          echo "<br />\n";
      }
    }
  }
};


class CHAW_preferences
{

  function CHAW_preferences()
  {
  }

  function get_elementtype()
  {
    return HAW_USERDEFINED;
  }

  function create(&$deck)
  {
    global $_CAMILA;
    if ($deck->ml == HAW_HTML && $_CAMILA['output'] == HAW_OUTPUT_AUTOMATIC)
    {
      echo "<div id='camilapreferences'>";
      echo "<div id='camilaapplicationname'><div>".CAMILA_APPLICATION_NAME."</div><div id='camilaapplicationtitle'>",$_CAMILA['app_title']."</div></div>\n<div id='camilapreferencesbar'>";
      if (!(isset($_REQUEST['camila_print'])) && $_CAMILA['user_loggedin']){
      $link=$_SERVER['PHP_SELF'];
      if ($_SERVER['QUERY_STRING']!="")
        $link.="?".urldecode($_SERVER['QUERY_STRING'])."&camila_preferences";
      else
        $link.="?camila_preferences";
      if (!CAMILA_ANON_LOGIN)
          echo $_CAMILA['user_surname'].' '.$_CAMILA['user_name']."&nbsp;|&nbsp;";
      echo "<a href='".$link."'>".camila_get_translation('camila.prefs')."</a>";

      if (!CAMILA_ANON_LOGIN) {
          if (CAMILA_HELPURL){
              echo "&nbsp;|&nbsp;";
              echo "<a href='".CAMILA_HELPURL."' target='_blank'>" . camila_get_translation('camila.help') . "</a>";
          }
          global $_CAMILA;
          if ($_CAMILA['user_id']!='')
            echo "&nbsp;|&nbsp;<a href='" . CAMILA_LOGOUT_URL . "'>".camila_get_translation('camila.logout')."</a>";
      }
    }
      echo '</div></div>';
    }
  }
};

class CHAW_storepwd
{

  function CHAW_storepwd()
  {
  }

  function get_elementtype()
  {
    return HAW_USERDEFINED;
  }

  function create(&$deck)
  {
    global $_CAMILA;
    if ($deck->ml == HAW_HTML && $_CAMILA['output'] == HAW_OUTPUT_AUTOMATIC)
    {
      echo "<script language=\"JavaScript\" type=\"text/javascript\" src=\"".CAMILA_DIR."js/login_storepwd.js\"></script>";
      //echo "<a href=\"javascript:store()\"><img src=\"images/storepwd.png\" border=\"0\" alt=\"Memorizza codice numerico su questo computer\"/></a>";
    }
  }
};


class CHAW_getpwd
{

  function CHAW_getpwd($c1,$c2,$c3)
  {
    $this->c1=$c1;
    $this->c2=$c2;
    $this->c3=$c3;
  }

  function get_elementtype()
  {
    return HAW_USERDEFINED;
  }

  function create(&$deck)
  {
    if ($deck->ml == HAW_HTML)
    {
      echo "<div style=\"text-align:left; position:relative;\">";
      echo "<form action=\"login.php\" method=\"post\" onsubmit=\"return get(".$this->c1.",".$this->c2.",".$this->c3.");\">";
      include "login_js.php";
      echo "<script language=\"JavaScript\" type=\"text/javascript\" src=\"". CAMILA_DIR ."js/login_getpwd.js\"></script>";
      echo "</div>";
    }
  }
};

class CHAW_js
{

  function CHAW_js($js, $force='false')
  {
    $this->js=$js;
    $this->force=$force;
  }

  function get_elementtype()
  {
    return HAW_USERDEFINED;
  }

  function create(&$deck)
  {
    global $_CAMILA;

    if ( (!isset($_REQUEST['camila_print'])) && ($_CAMILA['output'] == HAW_OUTPUT_AUTOMATIC) && ($deck->ml == HAW_HTML) && ( ($this->force==true) || ($_CAMILA['javascript_enabled'] == '1') ) )
    {
      echo $this->js;
    }
  }
};


class CHAW_form extends HAW_form
{
  var $_collapsible_pending = false;

  function camila_collapsible_start($id,$expand=true,$title='')
  {
    if ($expand)
        $code = "<div class='camilacollapsibleon'>";
    else
        $code = "<div class='camilacollapsibleoff'>";

    $code.=$title."<div id='camilacollapsible_".$id."'>";
    $js = new CHAW_js($code);
    $this->add_userdefined($js);
    
  }

  function camila_collapsible_end()
  {
    $code = "</div></div>";
    $js = new CHAW_js($code);
    $this->add_userdefined($js);
  }
  
  function add_submit($submit)
  {

    if (isset($_REQUEST['camila_print'])) {
	    $myHiddenElement = new CHAW_hidden('camila_print', 'cm');
	    $this->add_hidden($myHiddenElement);

	}
	
	if ($this->_collapsible_pending)
	    $this->camila_collapsible_end();
	
    $this->_collapsible_pending = false;
    
    parent::add_submit($submit);
  }


  function create(&$deck)
  {
    // add hawoutput query parameter to form, if required
    if ($deck->hawoutput)
    {
      $hidden_hawoutput = new HAW_hidden("hawoutput", $deck->hawoutput);
      $this->add_hidden($hidden_hawoutput);
    }

    // determine all elements that have to be submitted

    $i = 0;
    $varnames = null;
    reset($this->element); //set the array pointer to first element
    while (list($key, $val) = each($this->element))
    {
      switch ($val->get_elementtype())
      {
        case HAW_INPUT:
        case HAW_TEXTAREA:
        case HAW_SELECT:
        case HAW_CHECKBOX:
        case HAW_RADIO:
        case HAW_HIDDEN:
        {
          $element = $val;
          $varnames[$i] = $element->get_name();
          $i++;
        }
      }
    }

    if ($deck->ml == HAW_HTML)
    {
      // start tag of HTML form
      if ($this->method == HAW_METHOD_POST)
      //Camila Framework: Mod (enctype added)
        $method = " method=\"post\" enctype=\"multipart/form-data\"";
      else
        $method = " method=\"get\"";

      printf("<form action=\"%s\"%s>\n", $this->url, $method);

      if ($deck->xhtml)
        echo "<div id=\"hawform\">\n"; // needed for validation
    }
      // not necessary in WML, HDML and VoiceXML!

    if ($deck->ml == HAW_VXML)
    {
      if ($this->voice_text || $this->voice_audio_src)
      {
        // create introducing audio output for VoiceXML form
    
        echo "<block><prompt>";
  
        HAW_voice_audio(HAW_specchar($this->voice_text, $deck),
                        $this->voice_audio_src, HAW_VOICE_PAUSE, $deck);

        echo "</prompt></block>\n";
      }
    }

    $i = 0;
    while (isset($this->element[$i]))
    {
      $form_element = $this->element[$i];
      switch ($form_element->get_elementtype())
      {
        case HAW_PLAINTEXT:
        case HAW_IMAGE:
        case HAW_TABLE:
        case HAW_INPUT:
        case HAW_TEXTAREA:
        case HAW_SELECT:
        case HAW_RADIO:
        case HAW_CHECKBOX:
        case HAW_HIDDEN:
        case HAW_RAW:
        case HAW_RULE:
        case HAW_PLUGIN:
        {
          $form_element->create($deck);
          break;
        }

        case HAW_SUBMIT:
        {
          $submit = $this->element[$i];
          $submit->create($deck, $varnames, $this->url, $this->method);
          break;
        }

      }

      $i++;
    }

    if ($deck->ml == HAW_HTML)
    {
      if ($deck->xhtml)
        echo "</div>\n";

      // terminate HTML form
      echo "</form>\n";
    }
  }

};


class CHAW_text extends HAW_text
{
  function set_id($id) {
      $this->id = $id;
  }

  function create(&$deck)
  {
    if ($deck->ml == HAW_HDML)
    {
      // HDML

      if ($deck->alignment != "left")
        $deck->hdmlcardset->add_display_content("<" . $deck->alignment . ">\n");

      // print text
      if ($this->text)
      {
        $content = sprintf("%s\n", HAW_specchar($this->text, $deck));
        $deck->hdmlcardset->add_display_content($content);
      }

      // create required amount of carriage return's
      $br = "";
      for ($i=0; $i < $this->br; $i++)
        $br .= "<br>\n";

      $deck->hdmlcardset->add_display_content($br);
    }
    elseif(($deck->ml == HAW_HTML) || ($deck->ml == HAW_WML))
    {
      // HTML or WML

      if (($deck->ml == HAW_HTML) && $deck->css_enabled && $this->css_class)
      {
        printf("<span class=\"%s\" id=\"%s\">\n", $this->css_class, $this->id);
      }
      
      if (($this->attrib & HAW_TEXTFORMAT_BOXED) && ($deck->ml == HAW_HTML))
      {
        // determine text and background color, if not already assigned

        if (!$this->color)
          $this->color = $deck->disp_bgcolor;
        if (!$this->color)
          $this->color = "#FFFFFF"; // default: white text

        if (!$this->boxcolor)
          $this->boxcolor = $deck->color;
        if (!$this->boxcolor)
          $this->boxcolor = "#000000"; // default: on black background

        if ($deck->css_enabled)
          printf("<div id=\"hawtextbox\" style=\"background-color:%s; margin:0px;\">\n",
                 $this->boxcolor);
        else
        {
          printf("<table border=\"0\" bgcolor=\"%s\" width=\"100%%\"><tr><td><font%s>\n",
                 $this->boxcolor, $deck->fontstyle_attrbs);
  
          // align text in (table-)box
          printf("<div id=\"hawtextbox\" align=\"%s\">\n", $deck->alignment);
        }

        // decrement line breaks because div/table already causes 1 br
        if ($this->br >= 1)
          $this->br--;
      }
      
      if ($this->attrib & HAW_TEXTFORMAT_BOLD)
        echo "<b>\n";

      if ($this->attrib & HAW_TEXTFORMAT_UNDERLINE)
      {
        if ($deck->css_enabled)
          echo "<span style=\"text-decoration:underline;\">\n";
        else
          echo "<u>\n";
      }

      if ($this->attrib & HAW_TEXTFORMAT_ITALIC)
        echo "<i>\n";

      if ($this->attrib & HAW_TEXTFORMAT_BIG)
        echo "<big>\n";

      if ($this->attrib & HAW_TEXTFORMAT_SMALL)
        echo "<small>\n";

      if (($deck->ml == HAW_HTML) && $this->color)
      {
        if ($deck->css_enabled)
          printf("<span style=\"color:%s;\">\n", $this->color);
        else
          printf("<font color=\"%s\">", $this->color);
      }

      // print text
      // Camila Framework: commented
      //if (isset($this->text))
      //  printf("%s\n", HAW_specchar($this->text, $deck));

      //Camila Framework - New block START
      $pieces = explode("\n", $this->text);
      $pcount=0;
      foreach ($pieces as $an_element)
      {
        if ($pcount>0)
          printf("<br/>%s\n", HAW_specchar($an_element, $deck));
        else
          printf("%s\n", HAW_specchar($an_element, $deck));
        $pcount++;
      }
      //Camila Framework - New block END


      if (($deck->ml == HAW_HTML) && $this->color)
      {
        if ($deck->css_enabled)
          echo "</span>";
        else
          echo "</font>";
      }

      if ($this->attrib & HAW_TEXTFORMAT_SMALL)
        echo "</small>\n";

      if ($this->attrib & HAW_TEXTFORMAT_BIG)
        echo "</big>\n";

      if ($this->attrib & HAW_TEXTFORMAT_ITALIC)
        echo "</i>\n";

      if ($this->attrib & HAW_TEXTFORMAT_UNDERLINE)
      {
        if ($deck->css_enabled)
          echo "</span>\n";
        else
          echo "</u>\n";
      }

      if ($this->attrib & HAW_TEXTFORMAT_BOLD)
        echo "</b>\n";

      if (($this->attrib & HAW_TEXTFORMAT_BOXED) && ($deck->ml == HAW_HTML))
      {
        if ($deck->css_enabled)
          echo "</div>\n";
        else
          echo "</div></font></td></tr></table>\n";
      }

      // create required amount of carriage return's
      for ($i=0; $i < $this->br; $i++)
        echo "<br />\n";

      if (($deck->ml == HAW_HTML) && $deck->css_enabled && $this->css_class)
        echo "</span>\n";
    }
    elseif($deck->ml == HAW_VXML)
    {
      // VoiceXML

      if ($this->voice_navigation)
      {
        // enable navigation (repeat/forward)
        static $block_counter = 0;
        printf("<block name=\"block%d\"><assign name=\"nav_counter\" expr=\"%d\"/><prompt>",
               $block_counter, $block_counter);        
      }
      else
        echo "<block><prompt>";
  
      $pause = $this->br * HAW_VOICE_PAUSE; // short pause for each break

      // remove leading commas, dots etc. which may appear after link objects
      HAW_voice_audio(ereg_replace("^[\?!,;.]", " ", HAW_specchar($this->voice_text, $deck)),
                      $this->voice_audio_src, $pause, $deck);
  
      echo "</prompt></block>\n";

      if ($this->voice_navigation)
      {
        // create artificial field to control VoiceXML sequencing
        printf("<field name=\"dummy%d\">\n", $block_counter);
        echo "<property name=\"timeout\" value=\"0.5s\"/>\n";
        echo "<grammar>[(hawhaw really rocks)]</grammar>\n";
        printf("<noinput><assign name=\"dummy%d\" expr=\"true\"/></noinput>\n", $block_counter);
        echo "</field>\n";

        // create block end where forward will go to
        printf("<block name=\"block%dend\"/>\n", $block_counter++);
      }
    }
  }
};


class CHAW_image extends HAW_image
{

  function set_id($id) {
      $this->id = $id;
  }

  function set_css_class($css_class)
  {
    $this->css_class = $css_class;
  }

  function create(&$deck)
  {
    if (isset($_SERVER['HTTP_ACCEPT']))
      $HTTP_ACCEPT = $_SERVER['HTTP_ACCEPT'];
    else
      $HTTP_ACCEPT = "";

    if ($deck->ml == HAW_HDML)
    {
      // HDML

      if ($deck->alignment != "left")
        $deck->hdmlcardset->add_display_content("<" . $deck->alignment . ">\n");

      if ($this->localsrc)
        $icon = sprintf(" icon=\"%s\"", $this->localsrc);
      else
        $icon = "";

      $content = sprintf("<img src=\"%s\" alt=\"%s\"%s>\n",
                         $this->src_bmp,
                         HAW_specchar($this->alt, $deck), $icon);

      $deck->hdmlcardset->add_display_content($content);

      // create required amount of carriage return's
      $br = "";
      for ($i=0; $i < $this->br; $i++)
        $br .= "<br>\n";

      $deck->hdmlcardset->add_display_content($br);
    }
    elseif (($deck->ml == HAW_HTML) || ($deck->ml == HAW_WML))
    {
      // HTML or WML

      $size = "";
      if ($this->html_width)
        $size .= sprintf(" width=\"%d\"", $this->html_width);
      if ($this->html_height)
        $size .= sprintf(" height=\"%d\"", $this->html_height);
      
      if ($deck->ml == HAW_HTML)
      {
        // HTML

        if ($deck->iModestyle && $this->chtml_icon)
        {
          // cHTML icon available ==> use this icon instead of bitmap
          printf("&#%d;", $this->chtml_icon);
        }
        elseif ($deck->MMLstyle && $this->mml_icon)
        {
          // MML icon available ==> use this icon instead of bitmap
          echo CHR(27) . "$" . $this->mml_icon . CHR(15);
        }
        else
        {
          // use HTML bitmap
          
          if ($deck->css_enabled)
            $style = " style=\"vertical-align:middle; border-style:none\"";
          else
            $style = " align=\"middle\" border=\"0\"";


          printf("<span class=\"%s\" id=\"%s\"><img src=\"%s\" alt=\"%s\"%s%s /></span>",$this->css_class, $this->id,
                 $this->src_html, HAW_specchar($this->alt, $deck), $size, $style);
        }

        // evaluate HTML break instruction
        if ($deck->MMLstyle)
          $br_command = "<br>\n"; // MML has problems with clear attribute
        elseif ($deck->xhtml)
          $br_command = "<br style=\"clear:both;\" />\n"; // XHTML does not know clear attribute
        else
          $br_command = "<br clear=\"all\" />\n";
      }
      else
      {
        // WML

        if ($this->localsrc)
          $localsrc = sprintf(" localsrc=\"%s\"", $this->localsrc);
        else
          $localsrc = "";

        if ($deck->gif_enabled && (substr(strtolower($this->src_html), -4) == ".gif"))
          // user agent is able to display the provided GIF image
          printf("<img src=\"%s\" alt=\"%s\"%s%s/>\n", $this->src_html,
                  HAW_specchar($this->alt, $deck), $localsrc, $size);

        elseif (strstr(strtolower($HTTP_ACCEPT), "image/vnd.wap.wbmp"))
          // user agent is able to display .wbmp image
          printf("<img src=\"%s\" alt=\"%s\"%s/>\n", $this->src_wbmp,
                  HAW_specchar($this->alt, $deck), $localsrc);

        elseif (strstr(strtolower($HTTP_ACCEPT), "image/bmp") && $this->src_bmp)
          // user agent is able to display .bmp and .bmp image is available
          printf("<img src=\"%s\" alt=\"%s\"%s/>\n", $this->src_bmp,
                  HAW_specchar($this->alt, $deck), $localsrc);

        else
          // hope that the user agent makes the best of it!
          printf("<img src=\"%s\" alt=\"%s\"%s/>\n", $this->src_wbmp,
                  HAW_specchar($this->alt, $deck), $localsrc);

        // break instruction in WML
        $br_command = "<br/>\n";
      }

      // create required amount of carriage return's
      for ($i=0; $i < $this->br; $i++)
        echo $br_command;
    }
    elseif ($deck->ml == HAW_VXML)
    {
      // VoiceXML

      if ($this->voice_text || $this->voice_audio_src)
      {
        // create image-related audio output for VoiceXML images

        echo "<block><prompt>";

        HAW_voice_audio(HAW_specchar($this->voice_text, $deck),
                        $this->voice_audio_src, HAW_VOICE_PAUSE, $deck);
  
        echo "</prompt></block>\n";
      }
    }
  }


};


class CHAW_table extends HAW_table
{
    var $camila_equal_columns = false;
};


class CHAW_row extends HAW_row
{

  
};


class CHAW_input extends HAW_input
{
function CHAW_input($name, $value, $label, $format="*M")
  {
    global $_CAMILA;
    $this->name = $name;
    //Camila Framework Mod: $value -> HAW_specchar($value)
    $this->value = HAW_specchar($value, $_CAMILA['page']);
    //$this->value = $value;
    $this->label = $label;
    $this->format = $format;
    $this->type = HAW_INPUT_TEXT;
    $this->mode = HAW_INPUT_ALPHABET;
    $this->br = 1;
    $this->voice_text = $label;
    $this->voice_audio_src = "";
    $this->voice_type = "digits";
    $this->voice_grammar = "";
    $this->voice_help = array();
    $this->voice_noinput = array();
    $this->voice_nomatch = array();
  }
};


class CHAW_textarea extends HAW_textarea
{
};


class CHAW_select extends HAW_select
{
};


class CHAW_radio extends HAW_radio
{
};


class CHAW_checkbox extends HAW_checkbox
{
  //Camila Framework Add
  var $br=1;

  function set_br($br)
  {
    if (!is_int($br) || ($br < 0))
      die("invalid argument in set_br()");

    $this->br = $br;
  }

  function create(&$deck)
  {
    if ($deck->ml == HAW_HTML)
    {
      // create HTML checkbox

      $state = ($this->is_checked() ? "checked=\"checked\"" : "");

      if ($deck->xhtml || $deck->desktopBrowser)
      {
        printf("<input type=\"checkbox\" name=\"%s\" id=\"%s\" %s value=\"%s\" />",
                $this->name, $this->name, $state, $this->value);

        //Camila Framework Mod BEGIN

        if ($this->cols == 2)
            printf(" <label style=\"display: inline-block;display: -moz-inline-box;width:200px;\" for=\"%s\">%s</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n",
                    $this->name, HAW_specchar($this->label, $deck));
        else
            printf(" <label for=\"%s\">%s</label>\n",
                    $this->name, HAW_specchar($this->label, $deck));


        // create required amount of carriage return's
        $br = '';
        for ($i=0; $i < $this->br-1; $i++)
          $br .= '<br />';
        echo $br;

        //Camila Framework Mod END
      }
      else
        printf("<input type=\"checkbox\" name=\"%s\" %s value=\"%s\" /> %s\n",
                $this->name, $state, $this->value,
                HAW_specchar($this->label, $deck));
        //Camila Framework Mod BEGIN
        // create required amount of carriage return's
        $br = '';
        for ($i=0; $i < $this->br; $i++)
          $br .= '<br />';
        echo $br;
        //Camila Framework Mod END
    }
    elseif ($deck->ml == HAW_WML)
    {
      // create WML checkbox
      printf("<select name=\"%s\" multiple=\"true\">\n", $this->name);
      printf("<option value=\"%s\">%s</option>\n",
             $this->value, HAW_specchar($this->label, $deck));
      printf("</select>\n");
    }
    elseif ($deck->ml == HAW_HDML)
    {
      // create HDML checkbox
      // HDML does not support the multiple option feature!
      // ==> trick: simulate checkbox by creating radio buttons [x] and [ ]

      $options = " key=\"$this->name\"";

      // create label above the radio buttons
      $cb = sprintf("%s\n", HAW_specchar($this->label, $deck));

      // create "checked" radio button
      $cb .= sprintf("<ce value=\"%s\">[x]\n", $this->value);

      // create "not checked" radio button
      $cb .= "<ce value=\"\">[ ]\n";

      // make user interactive choice card
      $deck->hdmlcardset->make_ui_card($options, $cb, HAW_HDML_CHOICE);
    }
    elseif ($deck->ml == HAW_VXML)
    {
      // create VoiceXML checkbox (field with boolean grammar type)

      printf("<field type=\"boolean\" name=\"%s\">\n", $this->name);

      if ($this->voice_text || $this->voice_audio_src)
      {
        echo "<prompt>";

        HAW_voice_audio(HAW_specchar($this->voice_text, $deck),
                        $this->voice_audio_src, 0, $deck);

        echo "</prompt>\n";
      }

      printf("<filled><if cond=\"%s\"><assign name=\"%s\" expr=\"'%s'\"/><else/><assign name=\"%s\" expr=\"''\"/></if></filled>\n",
              $this->name, $this->name, $this->value, $this->name);

      // create event handlers
      HAW_voice_eventhandler("help",    $this->voice_help,    $deck);
      HAW_voice_eventhandler("noinput", $this->voice_noinput, $deck);
      HAW_voice_eventhandler("nomatch", $this->voice_nomatch, $deck);

      echo "</field>\n";
    }
  }
};


class CHAW_hidden extends HAW_hidden
{
  function create(&$deck)
  {
      if (isset($_REQUEST['camila_print']) && ($_REQUEST['camila_print']!='save'))
          return;
      else
          parent::create($deck);
  }

};


class CHAW_submit extends HAW_submit
{
};


class CHAW_link extends HAW_link
{

  function CHAW_link($label, $url, $title="")
  {

    if (isset($_REQUEST["camila_print"])) {
	    
	    $pos = strpos($url, "?");
 
       if ($pos === false) {
               $url.="?camila_print";
       } else {
               $url.="&camila_print";
       }
	    
	}

    $this->{get_parent_class(__CLASS__)}($label, $url, $title);

  }

    function _old_create(&$deck)
  {
    // add hawoutput query parameter to url, if required
    HAW_handle_forced_output($this->url, $deck->hawoutput);

    if ($this->url)
    {
      // inhibit "empty" links

      if ($deck->ml == HAW_HTML)
      {
        // create link in HTML
  
        $title_option = "";

        if ($this->title && ($deck->xhtml || $deck->lynx  || $deck->pureHTML))
          $title_option = sprintf(" title=\"%s\"", HAW_specchar($this->title, $deck));

        $accesskey_option = "";

        if (($this->accesskey != HAW_NO_ACCESSKEY) &&
            ($deck->iModestyle || $deck->xhtml))
          $accesskey_option = sprintf(" accesskey=\"%s\"", $this->accesskey);
  
        if ($deck->MMLstyle && ($this->accesskey != HAW_NO_ACCESSKEY))
          $accesskey_option = sprintf(" directkey=\"%s\"", $this->accesskey);
  
        // create required amount of carriage return's
        $br = "";
        for ($i=0; $i < $this->br; $i++)
          $br .= "<br />";
  
        printf("<a href=\"%s\"%s%s>%s</a>%s\n",
               HAW_specchar($this->url, $deck), $title_option, $accesskey_option,
               HAW_specchar($this->label, $deck), $br);
      }
  
      elseif ($deck->ml == HAW_WML)
      {
        // create link in WML
  
        if ($this->title)
          $title_option = sprintf(" title=\"%s\"",
                                   HAW_specchar($this->title, $deck));
        else
          $title_option = "";

        // create required amount of carriage return's
        $br = "";
        for ($i=0; $i < $this->br; $i++)
          $br .= "<br/>";
  
        printf("<a%s href=\"%s\">%s</a>%s\n",
               $title_option, HAW_specchar($this->url, $deck),
               HAW_specchar($this->label, $deck), $br);
      }
  
      elseif ($deck->ml == HAW_HDML)
      {
        // create link in HDML

        if ($this->title)
          $title_option = sprintf(" label=\"%s\"",
                                   HAW_specchar($this->title, $deck));
        else
          $title_option = "";
  
        if ($this->accesskey != HAW_NO_ACCESSKEY)
          $accesskey_option = sprintf(" accesskey=\"%s\"", $this->accesskey);
        else
          $accesskey_option = "";

        // create required amount of carriage return's
        $br = "";
        for ($i=0; $i < $this->br; $i++)
          $br .= "<br>";

        $content = sprintf("<a task=\"go\" dest=\"%s\"%s%s>%s</a>%s\n",
                            HAW_specchar($this->url, $deck),
                            $title_option, $accesskey_option,
                            HAW_specchar($this->label, $deck), $br);

        $deck->hdmlcardset->add_display_content($content);
      }

      elseif ($deck->ml == HAW_VXML)
      {
        // remove http:// from link label, as voice browsers complain about :
        //        (and users can't speak complete url anyway)
        $label = ereg_replace("^http://", "", strtolower(HAW_specchar($this->label, $deck)));

        // * / ? and = are not allowed in GSL grammar???
        $label = ereg_replace("[\*\/\?=]", "", $label);

        // ampersand not allowed in GSL grammar???
        $label = ereg_replace("&amp;", "", $label);

        if ($this->accesskey != HAW_NO_ACCESSKEY)
          $dtmf = sprintf(" dtmf=\"%s\"", $this->accesskey);
        else
          $dtmf = "";

        // prepare tag for VoiceXML link (will be written at form end)
        $deck->voice_links .= sprintf("<link next=\"%s\"%s><grammar>[%s]</grammar></link>\n",
                                       HAW_specchar($this->url, $deck), $dtmf, $label);

        if ($this->voice_text || $this->voice_audio_src)
        {
          // create audio output for VoiceXML link

          echo "<block><prompt>";

          if ($deck->voice_jingle)
          {
            // play jingle before link label is spoken
            printf("<audio src=\"%s\"></audio>", $deck->voice_jingle);
          }

          if ($this->br > 0)
            $pause = $this->br * HAW_VOICE_PAUSE; // short pause for each break
          else
            $pause = HAW_VOICE_PAUSE; // at least a short break to make the link phrase detectable

          HAW_voice_audio(HAW_specchar($this->voice_text, $deck),
                          $this->voice_audio_src, $pause, $deck);

          echo "</prompt></block>\n";
        }

        // update deck timeout
        if ($deck->voice_timeout < $this->voice_timeout)
          $deck->voice_timeout = $this->voice_timeout;
      }
    }
  }
  

};


class CHAW_phone extends HAW_phone
{
};


class CHAW_linkset extends HAW_linkset
{
  function create(&$deck)
  {
    if ($this->number_of_elements > 0)
    {
      if ($deck->ml == HAW_HTML)
      {
        // create linkset in HTML

        if ($deck->css_enabled)
        {
          // create links inside a frame
          
          //Camila Framework - Line Mod
          echo "<div id=\"linkset\">\n";

          //Camila Framework Mod - BEGIN
          if (!$deck->lynx)
          {
            echo "<div id=\"nav\">\n";
            // create link list to avoid whitespace between links
            echo "<ul>\n";
            while (list($key, $val) = each($this->element))
            {
              echo "<li>\n";
              $val->create($deck); // create one list element for each link
              echo "</li>\n";
            }
            echo "</ul>\n";
            echo "<span id=\"navclear\"></span>\n";
            echo "</div>\n";
          }
          //Camila Framework Mod - END
          if ($deck->lynx)
          {
            // create link list to avoid whitespace between links
            echo "<ul>\n";
            while (list($key, $val) = each($this->element))
            {
              echo "<li>\n";
              $val->create($deck); // create one list element for each link
              echo "</li>\n";
            }
            echo "</ul>\n";
          }
          else
          {
            while (list($key, $val) = each($this->element))
            {
              //Camila Framework - Line Added
              echo "&nbsp;&lt;";
              $val->create($deck); // create one list element for each link
              //Camila Framework - Line Added
              echo "&gt;";
            }
          }

          echo "</div>\n";
        }
        else
          // create normal links for the small devices
          while (list($key, $val) = each($this->element))
            $val->create($deck);
      }

      elseif ($deck->ml == HAW_WML)
      {
        // create linkset in WML

        if ($deck->upbrowser &&
           ($deck->number_of_forms == 0) &&
           ($deck->number_of_links == 0) &&
           ($deck->number_of_phones == 0))
        {
          echo "<select>\n";

          while (list($key, $val) = each($this->element))
          {
            if ($val->get_title())
              $title = " title=\"" . HAW_specchar($val->get_title(), $deck) . "\"";

            printf("<option onpick=\"%s\"%s>%s</option>\n",
                   HAW_specchar($val->get_url(), $deck), $title,
                   HAW_specchar($val->get_label(), $deck));
          }

          echo "</select>\n";
        }
        else
          // create normal WML links
          while (list($key, $val) = each($this->element))
            $val->create($deck);
      }

      elseif ($deck->ml == HAW_HDML)
      {
        // create linkset in HDML

        while (list($key, $val) = each($this->element))
          $val->create($deck);
      }

      elseif ($deck->ml == HAW_VXML)
      {
        // create linkset for VoiceXML

        if ($this->voice_text || $this->voice_audio_src)
        {
          if ($this->voice_text != HAW_VOICE_ENUMERATE)
          {
            echo "<block><prompt>";

            HAW_voice_audio(HAW_specchar($this->voice_text, $deck),
                            $this->voice_audio_src, 0, $deck);

            echo "</prompt></block>\n";
          }
        }

        while (list($key, $val) = each($this->element))
          $val->create($deck);
      }
    }
  }  
};


class CHAW_raw extends HAW_raw
{
};


class CHAW_banner extends HAW_banner
{
};


class CHAW_rule extends HAW_rule
{
};


class CHAW_voicerecorder extends HAW_voicerecorder
{
};

?>
