<?php
require 'config.php';
include($config['root_dir'].'includes/bootstrap.inc');
include($config['root_dir'].'theme/normal_header.php');
connect();
$name = get_unique_name("datasets");
?>


<div id="primaryContentContainer">
  <div id="primaryContent">
  <h1>Home</h1>
  <div class="section">
  <p>Welcome to "The Hordeum Toolbox" (THT).<br>
  THT is the webportal for the data generated by the Barley Coordinated Agricultural Project (CAP) is funded by the National Institute for Food and Agriculture (NIFA) of the United States Department of Agriculture (USDA). THT contains SNP, phenotypic, and pedigree data from breeding germplasm in the Barley CAP. As a community resource, THT will integrate rapidly expanding SNP data sets with traditional phenotypic data, structural genomics, and gene expression profile data sets available from PLEXdb. The underlying premise is that these molecular level phenotypes manifest as traits for which plant breeders select.</p>
  <!-- Box Table B -->
 
  </div>
  <div class="section">
		
  <!-- Box Table B -->
  <p>
  <table cellpadding="0" cellspacing="0"><tbody>
  <tr>
  <th>Search Type</th>
  <th>&nbsp;</th>
  </tr>
	
  <tr><td>
  <select onchange="window.open('<?php echo $config['base_url']; ?>search_bp.php?table=CAPdata_programs&uid='+this.options[this.selectedIndex].value,'_top')">
  <option selected value=''>Search by Breeding Program</option>
   <?php
  $sql = "select distinct data_program_name, data_program_code, CAPdata_programs_uid as uid
		  FROM CAPdata_programs
		  WHERE program_type = 'breeding'
		  order by data_program_name asc";
$r = mysql_query($sql) or die("<pre>" . mysql_error() . "\n$sql");
while($row = mysql_fetch_assoc($r)) {
  $progname = $row['data_program_name']." - ".$row['data_program_code'];
  $uid = $row['uid'];
  echo "<option value='$uid'>$progname</option>\n";
 }
?>
  </select></td>
  <td>All experiments containing data from the program&#39;s lines</td>
  </tr>

  <tr><td>
  <select onchange="window.open('<?php echo $config['base_url']; ?>search_phenotype.php?table=experiments&pheno_name='+this.options[this.selectedIndex].value,'_top')">
  <option selected value="">Search by Phenotype</option>
  <?php
  $sql = "select distinct phenotypes_name from phenotypes
  order by phenotypes_name";
$r = mysql_query($sql) or die("<pre>" . mysql_error() . "\n$sql");
while($row = mysql_fetch_assoc($r)) {
  $pheno_name = $row['phenotypes_name'];
  echo "<option value='$pheno_name'>$pheno_name</option>\n";
 }
?>
</select></td>
<td>All experiments that measure the trait</td></tr>

  <tr><td>
  <select onchange="window.open('<?php echo $config['base_url']; ?>view_search_yr2.php?table=experiments&year='+this.options[this.selectedIndex].value,'_top')">
  <option selected value=''>Search by Year</option>
  <?php
  $sql = "select distinct experiment_year from experiments
  order by experiment_year";
$r = mysql_query($sql) or die("<pre>" . mysql_error() . "\n$sql");
while($row = mysql_fetch_assoc($r)) {
  $year = $row['experiment_year'];
  echo "<option value='$year'>$year</option>\n";
 }
?>
</select></td>
<td>All experiment data from the selected year</td></tr>

</tbody></table></div></div></div>

<?php 
  $footer_div=1;
include($config['root_dir'].'theme/footer.php'); ?>