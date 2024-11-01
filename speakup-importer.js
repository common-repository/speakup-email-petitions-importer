
function getQueryParams(qs) {
    qs = qs.split("+").join(" ");
    var params = {},
        tokens,
        re = /[?&]?([^=]+)=([^&]*)/g;

    while (tokens = re.exec(qs)) {
        params[decodeURIComponent(tokens[1])]
            = decodeURIComponent(tokens[2]);
    }

    return params;
}




jQuery(document).ready(function(){
	if(jQuery('.petitions_page_dk_speakup_signatures .dk_speakup_clear form').length>0){
		var $_GET = getQueryParams(document.location.search);
		if($_GET['pid']!=null){
			jQuery('.petitions_page_dk_speakup_signatures .dk_speakup_clear form').append('<a href="admin.php?page=speakup-import&pid='+$_GET['pid']+'" class="button dk-speakup-inline">'+speakupimport.import_button+'</a>');
		}
	}
});
