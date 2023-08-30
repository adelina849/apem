<script type="text/javascript">
function ajaxfilemanager(field_name, url, type, win) {
     switch (type) {
        case "image":
        break;
        case "media":
        break;
        case "flash": 
        break;
        case "file":
        break;
        default:
        return false;
    }
    tinyMCE.activeEditor.windowManager.open({
        url: base_url+"assets/tinymce/jscripts/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php",
        width: 1500,
        height: 540,
        inline : "yes",
        close_previous : "no"
    },{
        window : win,
        input : field_name
    });
}
    tinyMCE.init({

          // General options
          mode : "textareas",
          elements : "ajaxfilemanager",
          file_browser_callback : 'ajaxfilemanager',
          theme : "advanced",
          skin : "o2k7",
          skin_variant : "silver",
          plugins : "safari,pagebreak,style,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount",

          // Theme options //BAWAAN
          // theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
          // theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
          // theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
          // theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
		  
		  
		  theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect,cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
          theme_advanced_buttons2 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen,insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",

          theme_advanced_toolbar_location : "top",
          theme_advanced_toolbar_align : "left",
          theme_advanced_statusbar_location : "bottom",
          theme_advanced_resizing : true,
          relative_urls : false,
          remove_script_host : false,
          // Example content CSS (should be your site CSS)
          content_css : base_url+"assets/tinymce/css/content.css",

          // Drop lists for link/image/media/template dialogs
          

          // Replace values for the template plugin
          template_replace_values : {
           username : "Some User",
           staffid : "991234"
       }
   });
</script>