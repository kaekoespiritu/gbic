<?php

 
   function pagination($query, $per_page,$page, $site, $position, $search, $reportType, $period, $url = '?'){        
    	$query = "SELECT COUNT(*) as `num` FROM {$query}";
    	$row = mysql_fetch_array(mysql_query($query));
    	$total = $row['num'];
        $adjacents = "2";
        $site = $site;
        $position = $position;
    	$page = ($page == 0 ? 1 : $page);  
    	$start = ($page - 1) * $per_page;								
		
    	$prev = $page - 1;							
    	$next = $page + 1;
        $lastpage = ceil($total/$per_page);
    	$lpm1 = $lastpage - 1;
    	
    	$pagination = "";
    	if($lastpage > 1)
    	{	
    		$pagination .= "<ul class='pagination'>";
                    $pagination .= "<li class='details' style='margin-top:2px'>Page $page of $lastpage</li>";
    		if ($lastpage < 7 + ($adjacents * 2))
    		{	
    			for ($counter = 1; $counter <= $lastpage; $counter++)
    			{
    				if ($counter == $page)
    					$pagination.= "<li><a class='current'>$counter</a></li>";
    				else
    					$pagination.= "<li><a href='{$url}page=$counter&site={$site}&position={$position}&search={$search}&type={$reportType}&period={$period}'>$counter</a></li>";					
    			}
    		}
    		elseif($lastpage > 5 + ($adjacents * 2))
    		{
    			if($page < 1 + ($adjacents * 2))		
    			{
    				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}page=$counter&site={$site}&position={$position}&search={$search}&type={$reportType}&period={$period}'>$counter</a></li>";					
    				}
    				$pagination.= "<li class='dot'>...</li>";
    				$pagination.= "<li><a href='{$url}page=$lpm1&site={$site}&position={$position}&search={$search}&type={$reportType}&period={$period}'>$lpm1</a></li>";
    				$pagination.= "<li><a href='{$url}page=$lastpage&site={$site}&position={$position}&search={$search}&type={$reportType}&period={$period}'>$lastpage</a></li>";		
    			}
    			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
    			{
    				$pagination.= "<li><a href='{$url}page=1&site={$site}&position={$position}&search={$search}&type={$reportType}&period={$period}'>1</a></li>";
    				$pagination.= "<li><a href='{$url}page=2&site={$site}&position={$position}&search={$search}&type={$reportType}&period={$period}'>2</a></li>";
    				$pagination.= "<li class='dot'>...</li>";
    				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}page=$counter&site={$site}&position={$position}&search={$search}&type={$reportType}&period={$period}'>$counter</a></li>";					
    				}
    				$pagination.= "<li class='dot'>..</li>";
    				$pagination.= "<li><a href='{$url}page=$lpm1&site={$site}&position={$position}&search={$search}&type={$reportType}&period={$period}'>$lpm1</a></li>";
    				$pagination.= "<li><a href='{$url}page=$lastpage&site={$site}&position={$position}&search={$search}&type={$reportType}&period={$period}'>$lastpage</a></li>";		
    			}
    			else
    			{
    				$pagination.= "<li><a href='{$url}page=1&site={$site}&position={$position}&search={$search}&type={$reportType}&period={$period}'>1</a></li>";
    				$pagination.= "<li><a href='{$url}page=2&site={$site}&position={$position}&search={$search}&type={$reportType}&period={$period}'>2</a></li>";
    				$pagination.= "<li class='dot'>..</li>";
    				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}page=$counter&site={$site}&position={$position}&search={$search}&type={$reportType}&period={$period}'>$counter</a></li>";					
    				}
    			}
    		}
    		
    		if ($page < $counter - 1){ 
    			$pagination.= "<li><a href='{$url}page=$next&site={$site}&position={$position}&search={$search}&type={$reportType}&period={$period}'>Next</a></li>";
                $pagination.= "<li><a href='{$url}page=$lastpage&site={$site}&position={$position}&search={$search}&type={$reportType}&period={$period}'>Last</a></li>";
    		}else{
    			$pagination.= "<li><a class='current'>Next</a></li>";
                $pagination.= "<li><a class='current'>Last</a></li>";
            }
    		$pagination.= "</ul>\n";		
    	}
    
    
        return $pagination;
    } 
?>