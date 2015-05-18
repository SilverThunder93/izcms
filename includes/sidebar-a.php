		<div id="content-container">
			<div id="section-navigation">
				<ul class="navi">
					<?php 
						//Cau lenh truy xuat categories
						$q = "SELECT cat_name, cat_id FROM categories ORDER BY position ASC";
						$r = mysqli_query($dbc, $q);
						confirm_query($r, $q);

						//Lay categories tu CSDL
						while ($cats = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
							echo "<li><a href='index.php'>" . $cats['cat_name'] . "</a>";

								//Cau lenh truy xuat pages
								$q1 = "SELECT page_name, page_id FROM pages WHERE cat_id={$cats['cat_id']} ORDER BY position ASC";
								$r1 = mysqli_query($dbc, $q1);
								confirm_query($r1, $q1);

								echo "<ul class='pages'>";
								//lay pages tu CSDL
								while ($pages = mysqli_fetch_array($r1, MYSQLI_ASSOC)) {
									echo "<li><a href=''>" . $pages['page_name'] . "</a></li>";
								}//End WHILE pages
								echo "</ul>";
							echo "</li>";
						} // End WHILE cats
					?>					
				</ul><!--end ul.navi-->
			</div><!-- end #section-navigation -->