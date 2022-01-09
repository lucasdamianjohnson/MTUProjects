<?php
define("STORIES_PER_PAGE",15);//How many stories are on a page
define("PAGINATION_PAGES",10);//Max number of pages in pagination at a time. Make this even please.

if(isset($_GET['num'])) {
	$num = $_GET['num'];
} else {
	$num = 15;
}

if(isset($_GET['title'])) {
	$title = $_GET['title'];
} else {
	$title = 'Michigan Tech In The News';
}
if(isset($_GET['page'])) {
	$page = $_GET['page'];
} else {
	$page =1;
}
if($page == 1 ){
	$start = 0;
} else {
$start = ($page - 1) * 15;
}
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
ini_set('default_charset', 'UTF-8');
include "/docroot/htdocs/mtu_resources/php/google/getsheet/sheet-parser.php";
?>


<?php
$items = array();
$parser = new SheetParser("16UjRZLOM5Rbh4k6vEJqinEgCsrQtc5HlAiw6g7M9NI8");
$parser->setColumns(6);
$sheets = $parser->get();
//loop through all of the urls
echo '<div id="results">';
echo '<div class="grid-row in-the-news">';
echo '<ul class="grid-x none">';
foreach ($sheets as $title => $sheet) {
	rsort($sheet);
	
	$total = count($sheet);
	
	foreach ($sheet as $index => $row) {
		if($index > $start && $num > 0) {
			$timestamp = $row[1];
			$email = $row[2];
			$newsEntity = $row[3];
			$date = $row[4];
			$headline = $row[5];
			$url = $row[6];
				
?>
			
				<li class="column medium-4">
					<div class="name"><?php echo $newsEntity; ?></div>
					<div class="news-date"><?php echo date('M j, Y',strtotime($timestamp)); ?></div>
					<h3><a href="<?php echo $url; ?>"><?php echo $headline; ?></a></h3>
				</li>	

<?php
		
			$num--;
		}
		
	
	}
}
echo "</ul></div>";


$filters = array('page'=>$page);
create_pagination($total,$page,$filters);
echo "</div>";
/**
 * Function for generating the pagination for a page.
 *
 * @param $num_results  The total number of results that are
 * possible for the query in question, that way we know how
 * many pages to expect.
 *
 * @param $page The page that is being requested
 *
 * @param $filters The filters to tack onto the end url so that links are within the same filter criterion
 **/
function create_pagination($num_results,$page,$filters){
    if(STORIES_PER_PAGE < $num_results){
        echo "<div class=\"pagination center padding-top-2x\" id = \"pagination\">";
        $prev = $next = 0;//whether or not we should display previous or next links.
        if($page!= 1){//not the first page for given filters.
            $prev = 1;
            $filters["page"]= 1;//add the first page number to the filter vars so we can build it into the query string
            $queryString = http_build_query($filters);
						echo "<a href = \"?{$queryString}\" rel =\"{$queryString}\" data-page=".$filters["page"].">First</a> | ";
						
            $filters["page"]= ($page-1);//add the previous page number to the filter vars so we can build it into the query string
            $queryString = http_build_query($filters);
            echo "<a href = \"?{$queryString}\" rel =\"{$queryString}\" data-page=".$filters["page"].">Previous</a> | ";
        }
        
        $pages = ceil($num_results/STORIES_PER_PAGE);//the number of pages there will be. ceil so the fractional page at the end is still counted
        
        if($page != $pages && $pages!=0){//not at the end, and there was some results returned
            $next = 1;
        }
        
        $start_page;//start page for pagination
        $end_page;//end page for pagination
        
        if($page < PAGINATION_PAGES/2){
            $start_page = 0;
            if($pages > PAGINATION_PAGES){
                //The page they want is less than PAGINATION_PAGES/2, and there are going to be more pages beyond what is shown in the pagination,
                //so just end at the standard limit.
                $end_page = PAGINATION_PAGES;
            }
            else{
                //there's not going to be anymore pages coming up in the pagination, so display everything in it.
                $end_page = $pages;
            }
        }
        else{//we might need to slide the pagination at some point.
            
            //were in the middle of the pagination somewhere, so just display the pages ahead and behind
            if($pages - PAGINATION_PAGES/2 >= $page){
                $end_page = $page + PAGINATION_PAGES/2;
                $start_page = $page - PAGINATION_PAGES/2;
            }
            
            //we hit the end of the pagination
            else{
                $end_page = $pages;//set last page as the last page there is going to be
                if($pages > PAGINATION_PAGES/2){//if there passed the middle, then we don't want to shift the pagination anymore.
                    $start_page = $pages - (PAGINATION_PAGES < $pages ? PAGINATION_PAGES : $pages);
                }
                else{
                    $start_page = 0;
                }
            }
        }
        
        //output pagination for all of the pages from start_page to end_page
        for($i=$start_page;$i < $end_page;$i++){
            $filters["page"]=$i+1;
            $queryString = http_build_query($filters);
            if($page!=$filters["page"]){
                echo "<a href = \"?{$queryString}\" rel =\"{$queryString}\" data-page=".$filters["page"].">".$filters["page"]."</a> ";
            }
            else{
                echo "<a href = \"?{$queryString}\" rel =\"{$queryString}\" class=\"selected\" data-page=".$filters["page"].">".$filters["page"]."</a> ";
            }
        }
        
        //output the next button if necessary
        if($next){
            $filters["page"]= ($page+1);
            $queryString = http_build_query($filters);
            echo " | <a href = \"?{$queryString}\" rel =\"{$queryString}\" data-page=".$filters["page"].">Next</a> ";
						
            $filters["page"]= $pages;//add the last page number to the filter vars so we can build it into the query string
            $queryString = http_build_query($filters);
						echo " | <a href = \"?{$queryString}\" rel =\"{$queryString}\" data-page=".$filters["page"].">Last</a>";
					
        }
        
        echo "</div>";
    }
}




?>


