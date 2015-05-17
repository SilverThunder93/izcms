		<div id="content-container">
			<div id="section-navigation">
				<ul class="navi">
					<?php 
						$q = "SELECT cat_name FROM categories ORDER BY position ASC";
						$r = mysqli_query($dbc, $q) or die("Query ($q) \n<br/> MySQL Error: " . mysqli_error($dbc));
						while ($cats = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
							echo "<li><a href='index.php'>" . $cats['cat_name']. "</a></li>";
						}
					?>					
				</ul><!--end ul.navi-->
			</div><!-- end #section-navigation -->