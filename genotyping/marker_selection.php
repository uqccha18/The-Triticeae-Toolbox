<?php
/*
* Logged in page initialization
*
* 9/2/2010   J.Lee modify to add new snippet Gbrowse tracks
* 8/29/2010  J.Lee modify to not use iframe for link to Gbrowse   
*/
$usegbrowse = True;
require 'config.php';
include($config['root_dir'].'includes/bootstrap.inc');
connect();
session_start();
include($config['root_dir'].'theme/admin_header.php');
?>

<div id="primaryContentContainer">
  <div id="primaryContent">
  <h2> Select Markers</h2>
  <br>
  <div class="boxContent">
  <h3>Currently selected markers</h3>
  <?php
  function get_submitted_mapid() {
  $us_mapname=$_POST['mapname'] or die('select markers where?');
  $sql = "select map_uid from map
where map_name='" . mysql_real_escape_string($us_mapname) . "'";
  $sqlr = mysql_fetch_assoc(mysql_query($sql));
  return $sqlr['map_uid'];
}  
if (isset($_POST['selMkrs']) || isset($_POST['selbyname'])) {
    $mapid = get_submitted_mapid();
    if (isset($_POST['selMkrs'])) 
      $selmkrs=$_POST['selMkrs'];
    else {
      $selbyname = $_POST['selbyname'];
      $sql = "select m.marker_uid from markers as m inner join
markers_in_maps as mm using(marker_uid) where mm.map_uid=$mapid and
m.marker_name='" . mysql_real_escape_string($selbyname) . "'";
      $sqlr = mysql_fetch_assoc(mysql_query($sql));
      $selmkrs = array($sqlr['marker_uid']);
    }
    $mapids = $_SESSION['mapids'];
    if (!isset($mapids) || !is_array($mapids))
      $mapids = array();
    $clkmkrs=$_SESSION['clicked_buttons'];
    if (!isset($clkmkrs) || ! is_array($clkmkrs)) $clkmkrs=array();
    foreach($selmkrs as $mkruid) {
      if (! in_array($mkruid, $clkmkrs)) {
	array_push($clkmkrs, $mkruid);
	array_push($mapids, $mapid);
      }
    }
    $_SESSION['clicked_buttons'] = $clkmkrs;
    $_SESSION['mapids'] = $mapids;
  }
if (isset($_POST['deselMkrs'])) {
  $selmkrs=$_SESSION['clicked_buttons'];
  $mapids=$_SESSION['mapids'];
  foreach ($_POST['deselMkrs'] as $mkr) {
    if (($mkridx=array_search($mkr, $selmkrs)) !== false) {
      array_splice($selmkrs, $mkridx,1);
      array_splice($mapids, $mkridx, 1);
    }
  }
  $_SESSION['clicked_buttons']=$selmkrs;
  $_SESSION['mapids']=$mapids;
 }
print "<form id=\"deselMkrsForm\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">";
print "<table><tr><td>\n";
print "<select id=\"mlist\" name=\"deselMkrs[]\" multiple=\"multiple\" size=10>";
$mapids = $_SESSION['mapids'];
if (!isset($mapids) || !is_array($mapids))
  $mapids = array();
reset($mapids);
$chrlist = array();
foreach ($_SESSION['clicked_buttons'] as $mkruid) {
  $mapid = current($mapids);
  next($mapids);
  $sql = "select m.marker_name, mm.chromosome
from markers as m inner join markers_in_maps as mm using(marker_uid)
where marker_uid=$mkruid" . ($mapid ? " and mm.map_uid=$mapid":"");
  $result=mysql_query($sql)
    or die("invalid marker uid\n");
  while ($row=mysql_fetch_assoc($result)) {
    $selval=$row['marker_name'];
    array_push($chrlist, $row['chromosome']);
    print "<option value=\"$mkruid\">$selval</option>\n";
  }
}
$chrlist = array_unique($chrlist);
$chrlist.sort();
print "</select>";
print "</td><td>\n";
echo "<script type='text/javascript'>
var mlist = \$j('#mlist option').map(function () { return \$j(this).text(); });
</script>";
foreach ($chrlist as $chr) {
  // echo "$chr <br />\n";
  echo "<div id='gbrowse_$chr'></div>\n";
  echo <<<EOD
<script type="text/javascript">
    \$j('#gbrowse_$chr')
    .bind('ajaxSend',
	  function () {
	    \$j(this).html("<p>Loading track for chromosome $chr...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>");
	    \$j(this).addClass('inprogress');
	  })
    .bind('ajaxComplete',
	  function () { \$j(this).removeClass('inprogress'); });
  \$j(document).ready(function () {
      var hilite = [], index;
      for (index=0; index<mlist.length; ++index)
	hilite.push(mlist[index] + '@orange');
      loadGbrowse("#gbrowse_$chr", " #details_panel",
		  ["name=" + encodeURIComponent('chr$chr'),
		   "h_feat=" + encodeURIComponent(hilite.join(' ')),
		   'label=' + encodeURIComponent('Marker OWB_2383'),
		   'label=' + encodeURIComponent('Marker UCR04162008'),
           'label=' + encodeURIComponent('Marker SteptoeMorex'),
           'label=' + encodeURIComponent('Marker MorexBarke'),
		   'grid=on', 'show_tooltips=on',
		   '.cgifields=show_tooltips', 'drag_and_drop=on']
		  .join('&'),
		  function () {
		    // \$j("#gbrowse_$chr").find("area[href^='?ref']")
		    //   .each(function () {
		    // 	  \$j(this).removeAttr('href');
		    // 	});
		    \$j("#gbrowse_$chr")
		      .prepend("<p>Chromsome $chr</p>");
		    // \$j("#mlist")
		    //   .change(function () {
		    // 	  \$j("#mlist option:selected")
		    // 	    .map(function () {
		    // 		var outerThis = this;
		    // 		var filter = "#gbrowse_$chr area[href]";
		    // 		\$j(filter)
		    // 		  .filter(function ()
		    // 			  {
		    // 			    return \$j(this).attr('href').indexOf("name=" + \$j(outerThis).text() + ";") != -1; }).trigger('mouseover');
		    // 	      });
		    // 	});
		  });
    });
</script>
EOD;
}
print "</td></tr></table>\n";
print "<p><input type=\"submit\" value=\"remove marker\" style=\"color: black\" /></p>";
print "</form>";

// store the selected markers into the database
$username=$_SESSION['username'];
if (! isset($username) || strlen($username)<1) $username="Public";
store_session_variables('clicked_buttons', $username);
store_session_variables('mapids',$username);
?>
</div>
<div class="boxContent">
  <h3> Select Markers from the maps</h3>
  <a href="/cgi-bin/gbrowse/tht"> Link to GBrowse Maps</a>
  </div>
  <div id="markerSel" class="boxContent">
  <h3> Select Markers in a range</h3>
  <form id="markerSelForm" action="<?php echo $config['base_url']; ?>genotyping/marker_selection.php" method="post">
  <table id="markeSelTab" border=1>
  <thead>
  <tr> <td>Maps</td><td>Range</td><td>Markers</td></tr>
  </thead>
  <tbody>
  <tr><td>
  <select name='mapname' size=10 onfocus="DispMapSel(this.value)" onchange="DispMapSel(this.value)">;
<?php
$result=mysql_query("select map_name from map") or die(mysql_error);
while ($row=mysql_fetch_assoc($result)) {
  $selval=$row['map_name'];
  print "<option value=\"$selval\">$selval</option>\n";
 }
?>
</select></td>
<td><p>Select a map from the maps column</p>
<p>on the left</p>
</td>
<td>
</td>
</tr>
</tbody>
</table>
<p><input type="submit" value="Select Markers"></p>
  </form>
  </div>
  </div>
  </div>
  </div>
  <?php include($config['root_dir'].'theme/footer.php'); ?>