<?php

/* This File is part of Camila PHP Framework
   Copyright (C) 2006-2008 Umberto Bresciani

   Camila PHP Framework is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   Camila PHP Framework is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with Camila Framework; if not, write to the Free Software
   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA */


  require_once(CAMILA_DIR.'datagrid/elements/form/textbox.php');

  class form_email extends form_textbox
  {
      function validate()
      {
          parent::validate();

          if ($this->value != '' and $this->maxlength > 0)
              $this->form->validator->email($this->field);
      }
  }
?>
