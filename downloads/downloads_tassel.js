/*global alert,$,$$,$A,$H,Prototype,Ajax,Template,Element,getXMLHttpRequest*/
var php_self = document.location.href;
var breeding_programs_str = "";
var phenotype_categories_str = "";
var phenotype_items_str = "";
var locations_str = "";
var years_str = "";
var lines_str = "";
var experiments_str = "";
var experiments_loaded = false;
var traits_loaded = false;
var markers_loaded = false;
var select1_str = "";
var select2_str = "";
var subset = "";
var trait_cmb = "";

var markerids = null;
var selmarkerids = [];

var markers_loading = false;
var traits_loading = false;

var title = document.title;

function load_title(command) {
    var url = php_self + "?function=refreshtitle&lines=" + lines_str + "&exps=" + experiments_str + '&pi=' + phenotype_items_str + '&subset=' + subset + '&cmd=' + command;
    var tmp = new Ajax.Updater($('title'), url, {asynchronous:false}, {
        onComplete : function() {
            $('title').show();
            document.title = title;
        }
    });
    url = "side_menu.php";
    tmp = new Ajax.Updater($('quicklinks'), url, {
    onComplete : function() {
      $('quicklinks').show();
      document.title = title;
    }
    });
}

function load_experiments() {
    $('step3').hide();
    var url = php_self + "?function=type1experiments&bp=" + breeding_programs_str + '&yrs=' + years_str;
    document.title='Loading Trials...';
    var tmp = new Ajax.Updater($('step3'),url,
    { 
                onComplete: function() {
                    $('step3').show();
                document.title=title;
                }
            }
        );
        experiments_loaded = true;
        //if (traits_loaded == true)
        //load_traits();
        //if (markers_loaded == true)
        //load_markers(100, 0);
    }

function load_locations() {
    $('step11').hide();
    var url = php_self + "?function=step1locations&bp=" + breeding_programs_str + '&yrs=' + years_str;
    document.title='Loading Step1...';
        var tmp = new Ajax.Updater($('step11'),url,
                    {
        onComplete: function() {
        $('step11').show();
        document.title=title;
        }
                }
    );
            }
function load_locations2() {
    $('step2').hide();
    var url = php_self + "?function=step2locations&loc=" + locations_str + '&yrs=' + years_str;
    document.title='Loading Step1...';
        var tmp = new Ajax.Updater($('step2'),url,
                {
        onComplete: function() {
        $('step2').show();
        document.title=title;
        }
                }
    );
        }
function load_locations3() {
    $('step3').hide();
    var url = php_self + "?function=step3locations&loc=" + locations_str + '&yrs=' + years_str;
    document.title='Loading Step1...';
        var tmp = new Ajax.Updater($('step3'),url,
                {
        onComplete: function() {
        $('step3').show();
        document.title=title;
        }
                }
    );
        }

function load_locations5() {
    $('step4b').hide();
    var url = php_self + "?function=step5locations&exps=" + experiments_str + '&pi=' + phenotype_items_str + '&subset=' + subset;
    document.title = 'Loading Step1...';
    var tmp = new Ajax.Updater($('step4b'), url, {
        onComplete : function() {
            $('step4b').show();
            document.title = title;
        }
    });
}

function load_programs5() {
    $('step4b').hide();
    var url = php_self + "?function=step5programs&exps=" + experiments_str + '&pi=' + phenotype_items_str + '&subset=' + subset;
    document.title = 'Loading Step1...';
    var tmp = new Ajax.Updater($('step4b'), url, {
        onComplete : function() {
            $('step4b').show();
            document.title = title;
        }
    });
}

function load_phenotypes2() {
    $('step2').hide();
    var url = php_self + "?function=step2phenotype&pc=" + phenotype_categories_str + "&yrs=" + years_str;
    document.title = 'Loading Step1...';
    var tmp = new Ajax.Updater($('step2'), url, {
        onComplete : function() {
            $('step2').show();
            document.title = title;
        }
    });
}

function load_phenotypes3() {
    $('step3').hide();
    var url = php_self + "?function=step3phenotype&pi=" + phenotype_items_str + "&trait_cmb=" + trait_cmb;
    document.title = 'Loading Step1...';
    var tmp = new Ajax.Updater($('step3'), url, {
        onComplete : function() {
            $('step3').show();
            document.title = title;
        }
    });
}

function load_phenotypes4() {
    $('step4').hide();
    var url = php_self + "?function=step4phenotype&pi=" + phenotype_items_str + "&e=" + experiments_str + "&lines=" + lines_str + '&subset=' + subset;
    document.title = 'Loading Step1...';
    var tmp = new Ajax.Updater($('step4'), url, {
        onComplete : function() {
            $('step4').show();
            document.title = title;
        }
    });
}

function load_phenotypes5() {
    $('step5').hide();
    var url = php_self + "?function=step5phenotype&pi=" + phenotype_items_str + "&e=" + experiments_str + "&lines=" + lines_str + '&subset=' + subset;
    document.title = 'Loading Step1...';
    var tmp = new Ajax.Updater($('step5'), url, {
        onCreate: function() { Element.show('spinner'); },
        onComplete : function() {
            $('step5').show();
            document.title = title;
            load_title();
        }
    });
}

function load_lines() {
    $('step11').hide();
    var url = php_self + "?function=step1lines&bp=" + breeding_programs_str + '&yrs=' + years_str;
    document.title = 'Loading Step1...';
    var tmp = new Ajax.Updater($('step11'), url, {
        onComplete : function() {
            $('step11').show();
            document.title = title;
        }
    });
}
function load_lines2() {
    $('step2').hide();
    var url = php_self + "?function=step2lines&bp=" + breeding_programs_str + '&yrs=' + years_str;
    document.title = 'Loading Step1...';
    var tmp = new Ajax.Updater($('step2'), url, {
        onComplete : function() {
            $('step2').show();
            document.title = title;
        }
    });
    document.getElementById('step3').innerHTML = "";
}

function load_lines3() {
    $('step3').hide();
    var url = php_self + "?function=step3lines&e=" + experiments_str + '&pi=' + phenotype_items_str;
    document.title = 'Loading Step1...';
    var tmp = new Ajax.Updater($('step3'), url, {
        onComplete : function() {
            $('step3').show();
            document.title = title;
        }
    });
    document.getElementById('step4').innerHTML = "";
}

function load_lines4() {
    $('step4').hide();
    var url = php_self + "?function=step4lines&e=" + experiments_str + '&pi=' + phenotype_items_str;
    document.title = 'Loading Step1...';
    var tmp = new Ajax.Updater($('step5'), url, {
        onComplete : function() {
            $('step4').show();
            document.title = title;
        }
    });
}

function load_traits()
{		
    traits_loading = true;		
    $('step4').hide();
    var url=php_self + "?function=type1traits&exps=" + experiments_str;
    document.title='Loading Traits...';
	var tmp = new Ajax.Updater(
	    $('step4'),url,
		{onComplete: function() {
            $('step4').show();  
            if (markers_loading === false) {
                document.title = title;
            }
            traits_loading = false;
            traits_loaded = true;
        }}
	);
}

function selectedTraits() {
                                var ret = '';
                                $A($('traitsbx').options).each(function(trait){
                                if (trait.selected)
                                        {
                                                ret += (ret === '' ? '' : ',') + trait.value;
                                        }
                                });
                                return ret;
                        }

function load_markers( mm, mmaf) {
    markers_loading = true;
    $('step5').hide();
    var url=php_self + "?function=type1markers&bp=" + breeding_programs_str + '&lines=' + lines_str + '&exps=' + experiments_str + '&t=' + selectedTraits() + '&mm=' + mm + '&mmaf=' + mmaf + '&subset=' + subset;
    document.title='Loading Markers...';
    var tmp = new Ajax.Updater($('step5'),url,
        {onCreate: function() { Element.show('spinner'); },
            onComplete: function() {
             $('step5').show();
            if (traits_loading === false) {
                document.title = title;
            }
            markers_loading = false;
            markers_loaded = true;
            load_title();
        }}
    );
}

function load_markers_loc( mm, mmaf) {
    markers_loading = true;
    $('step5').hide();
    var url=php_self + "?function=type2markers&exps=" + experiments_str + '&pi=' + phenotype_items_str + '&lines=' + lines_str + '&mm=' + mm + '&mmaf=' + mmaf + '&subset=' + subset;
    document.title='Loading Markers...';
    var tmp = new Ajax.Updater($('step5'),url,
        {onCreate: function() { Element.show('spinner'); },
            onComplete: function() {
             $('step5').show();
            if (traits_loading === false) {
                document.title = title;
            }
            markers_loading = false;
            markers_loaded = true;
            load_title();
        }}
    );
}

function load_breedprog() {
    $('step1').hide();
    var url = php_self + "?function=step1breedprog&bp=" + breeding_programs_str + '&yrs=' + years_str;
    document.title = 'Loading Step1...';
    var tmp = new Ajax.Updater($('step11'), url,
    {
        onComplete : function() {
            $('step1').show();
            document.title = title;
        }
    });
    document.getElementById('step2').innerHTML = "";
    document.getElementById('step3').innerHTML = "";
}

function update_breeding_programs(options) {
    breeding_programs_str = "";
    experiments_str = "";
    select1_str = "BreedingProgram";
    $A(options).each(
            function(breeding_program) {
                if (breeding_program.selected) {
                    breeding_programs_str += (breeding_programs_str === "" ? "" : ",") + breeding_program.value;
                }
            });
    if (breeding_programs_str !== "" && years_str !== "") {
        load_experiments();
    }
    document.getElementById('step3').innerHTML = "";
    document.getElementById('step4').innerHTML = "";
    document.getElementById('step5').innerHTML = "";
}
function update_locations(options) {
    locations_str = "";
    experiments_str = "";
    years_str = "";
    $A(options).each(
            function(locations){
                if (locations.selected) {
                    locations_str += (locations_str === "" ? "" : ",") + "'" + locations.value + "'";
                }
            }
    );
    load_locations2();
    document.getElementById('step3').innerHTML = "";
    document.getElementById('step4').innerHTML = "";
    document.getElementById('step4b').innerHTML = "";
    document.getElementById('step5').innerHTML = "";
}
function update_phenotype_categories(options) {
    phenotype_categories_str = "";
    phenotype_items_str = "";
    experiments_str = "";
    lines_str = "";
    experiments_str = "";
    $A(options).each(
                function(phenotype_categories) {
            if (phenotype_categories.selected) {
                phenotype_categories_str += (phenotype_categories_str === "" ? "" : ",") + phenotype_categories.value;
            }
        });
    load_phenotypes2();
    document.getElementById('step3').innerHTML = "";
    document.getElementById('step4').innerHTML = "";
    document.getElementById('step5').innerHTML = "";
}

function update_phenotype_items(options) {
    phenotype_items_str = "";
    lines_str = "";
    $A(options)
            .each(
                    function(phenotype_items) {
                        if (phenotype_items.selected) {
                            phenotype_items_str += (phenotype_items_str === "" ? ""
                                    : ",") + phenotype_items.value;
                        }
                    });
    if (select1_str == "Phenotypes") {
        load_phenotypes3();
        document.getElementById('step4').innerHTML = "";
    } else if (select1_str == "Locations") {
        load_locations5();
        //load_title();
        load_markers_loc('', '', 100, 0);
    } else {
        load_programs5();
        //load_title();
        load_markers('', '', 100, 0);
        document.getElementById('step5').innerHTML = "";
    }
    document.getElementById('step5').innerHTML = "";
}

function update_phenotype_trial(options) {
	experiments_str = ""; 
	lines_str = "";
	$A(options).each(function(experiment) {
		if (experiment.selected) {
			experiments_str += (experiments_str === "" ? "" : ",") + experiment.value;
		}
	});
	load_phenotypes4();
	load_title();
	load_phenotypes5();
}

function update_phenotype_trialb(options) {
/*used to update the trials with any/all traits */
    trait_cmb = options;
    load_phenotypes3();
    document.getElementById('step4').innerHTML = "";
}

function update_line_trial(options) {
    select1_str = "Lines";
    experiments_str = ""; 
    phenotype_items_str = "";
    $A(options).each(function(experiment) {
        if (experiment.selected) {
            experiments_str += (experiments_str === "" ? "" : ",") + experiment.value;
        }
    });
    load_lines3();
    load_lines4();
}

function update_line_pheno(options) {
    phenotype_items_str = "";
    $A(options).each(function(traits) {
        if (traits.selected) {
            phenotype_items_str += (phenotype_items_str === "" ? "" : ",") + traits.value;
        }
    });
    load_lines4();
}

function update_phenotype_lines(options) {
    lines_str = "";
    $A(options).each(function(lines) {
        if (lines.selected) {
            lines_str += (lines_str === "" ? "" : ",") + lines.value;
        }
    });
    if (select1_str == "Phenotypes") {
        load_phenotypes5();
    } else if (select1_str == "Locations") {
        load_markers_loc();
    } else if (select1_str == "BreedingProgram") {
        load_markers();
    }
}
function update_phenotype_linesb(options) {
/*used when updating radio button for line selection (session, all, combine) */
    subset = options;
    if (select1_str == "Phenotypes") {
        load_phenotypes4();
        load_phenotypes5();
    } else if (select1_str == "Locations") {
        load_locations5();
        load_markers_loc('', '', 100, 0);
    } else if (select1_str == "BreedingProgram") {
        load_programs5();
        load_markers('', '', 100, 0);
    }
}
			
		function update_years(options) {
			years_str = "";
			experiments_str = "";
			$A(options).each(function(year) {
				if (year.selected) {
						years_str += (years_str === "" ? "" : ",") + year.value;
					}
				});
			if (select1_str == "BreedingProgram") {
                    load_experiments();
            } else if (select1_str == "Locations") {
                    load_locations3();
            }
			document.getElementById('step3').innerHTML = "";
            document.getElementById('step4').innerHTML = "";
            document.getElementById('step4b').innerHTML = "";
            document.getElementById('step5').innerHTML = "";
		
		}
			
			function update_experiments(options) {
				experiments_str = ""; // clears experiment setting to avoid trait perserverance
				$A(options).each(function(experiment) {
					if (experiment.selected) {
						experiments_str += (experiments_str === "" ? "" : ",") + experiment.value;
					}
				});
                                document.getElementById('step4').innerHTML = "";
                                document.getElementById('step4b').innerHTML = "";
                                document.getElementById('step5').innerHTML = "";
				load_traits();
			}

			function load_phenotypes() {
			    $('step11').hide();
			    var url = php_self + "?function=step1phenotype&bp=" + breeding_programs_str + "&yrs=" + years_str;
			    document.title = 'Loading Step1...';
			    var tmp = new Ajax.Updater($('step11'), url, {
			        onComplete : function() {
			            $('step11').show();
			            document.title = title;
			        }
			    });
			    document.getElementById('step2').innerHTML = "";
			    document.getElementById('step3').innerHTML = "";
			    document.getElementById('step4').innerHTML = "";
			}

			function load_yearprog() {
			    $('step11').hide();
                var url=php_self + "?function=step1yearprog&bp=" + breeding_programs_str + "&yrs=" + years_str;
                document.title='Loading Step1...';
                var tmp = new Ajax.Updater($('step11'),url,
                        { 
                                onComplete: function() {
                                    $('step11').show();
                                    document.title=title;
                                }
                        }
                    );
                                document.getElementById('step2').innerHTML = "";
                                document.getElementById('step3').innerHTML = "";
                                document.getElementById('step4').innerHTML = "";
                }						
			
			function update_select1(options) {
			    select1_str = "";
			    breeding_programs_str = "";
			    phenotype_items_str = "";
			    experiments_str = "";
			    locations_str = "";
			    years_str = "";
			    $A(options).each(function(select1) {
			        if (select1.selected) {
			            select1_str = select1.value;
			        }
			    });
			    document.getElementById('step2').innerHTML = "";
                document.getElementById('step3').innerHTML = "";
                document.getElementById('step4').innerHTML = "";
                document.getElementById('step4b').innerHTML = "";
                document.getElementById('step5').innerHTML = "";
			    if (select1_str == "BreedingProgram") {
			      load_breedprog();
			    } else if (select1_str == "Years") {
			      load_yearprog();
			    } else if (select1_str == "Lines") {
			      load_lines();
			      load_lines2();
			    } else if (select1_str == "Locations") {
                              load_locations();
			    } else if (select1_str == "Phenotypes") {
			      load_phenotypes();
			    }
			    load_title();
			}  

			function update_select2(options) {
			    select2_str = "";
			    $A(options).each(function(select2) {
                                if (select2.selected) {
                                  select2_str = select2.value;
                                }
                            });
	        document.getElementById('step3').innerHTML = "";
                document.getElementById('step4').innerHTML = "";
                document.getElementById('step4b').innerHTML = "";
                document.getElementById('step5').innerHTML = "";
			} 

        function create_file(version) {
                document.getElementById('step5').innerHTML = "Creating data files";
                var url=php_self + "?function=download_session_" + version + "&bp=" + breeding_programs_str+'&yrs='+ years_str+'&e='+experiments_str;
                document.title='Creating data files...';
                var tmp = new Ajax.Updater($('step5'), url, {
                    onComplete: function() {
                        $('step5').show();
                        document.title = title;
                        Element.hide('spinner');
                        load_title();
                    }}
                );
            }
	
	function use_session(version) {
	        var mm = $('mm').getValue();
                var mmaf = $('mmaf').getValue();
                var mml = $('mml').getValue();
                markers_loading = true;
                Element.show('spinner');
                document.getElementById('step4b').innerHTML = "Selecting markers and calculating allele frequency for selected lines";
                document.getElementById('step5').innerHTML = "";
                var url=php_self + "?function=step5lines&pi=" + phenotype_items_str + '&yrs=' + years_str + '&exps=' + experiments_str + '&mm=' + mm + '&mmaf=' + mmaf + '&mml=' + mml + '&use_line=yes';
                document.title='Loading Markers...';
                var tmp = new Ajax.Updater($('step4b'), url, {
                    //onCreate: function() { Element.show('spinner'); },
                    onComplete: function() {
                         $('step4b').show();
                        document.title = title;
                        markers_loading = false;
                        markers_loaded = true;
                        create_file(version);
                    }}
                );
        }

			function get_download_loc(version) {
			    var mm = $('mm').getValue();
                var mmaf = $('mmaf').getValue();
                var url=php_self + "?function=type2_build_tassel_" + version + "&lines=" + lines_str+'&yrs='+ years_str+'&e='+experiments_str+'&pi='+phenotype_items_str+'&subset='+subset+'&mm='+mm+'&mmaf='+mmaf;
                document.location = url;
			}
			
			function load_markers_pheno( mm, mmaf) {
                markers_loading = true;
                $('step5').hide();
                var url=php_self + "?function=step5phenotype&pi=" + phenotype_items_str + '&yrs=' + years_str + '&e=' + experiments_str + '&mm=' + mm + '&mmaf=' + mmaf;
                document.title='Loading Markers...';
                //changes are right here
                var tmp = new Ajax.Updater($('step5'),url,
                    {onCreate: function() { Element.show('spinner'); },
                        onComplete: function() {
                        $('step5').show();
                        if (traits_loading === false) {
                            document.title = title;
                        }
                        markers_loading = false;
                        markers_loaded = true;
                        load_title();
                    }}
                );
            }

			function load_markers_lines( mm, mmaf, use_line) {
			    select1_str = "Lines";
                markers_loading = true;
                $('step5').hide();
                var url=php_self + "?function=step5lines&pi=" + phenotype_items_str + '&yrs=' + years_str + '&exps=' + experiments_str + '&mm=' + mm + '&mmaf=' + mmaf + '&use_line=' + use_line;
                document.title='Loading Markers...';
                //changes are right here
                var tmp = new Ajax.Updater($('step5'),url,
                    {onCreate: function() { Element.show('spinner'); },
                    onComplete: function() {
                         $('step5').show();
                        if (traits_loading === false) {
                            document.title = title;
                        }
                        markers_loading = false;
                        markers_loaded = true;
                        load_title();
                    }}
                );
            }
			function getdownload_qtlminer()
			{
				if (selectedTraits() === '') {
					alert('Please select at least one trait!');
					return false;
				}
			    var mm = $('mm').getValue();
                var mmaf = $('mmaf').getValue();
                var url=php_self + "?function=type1build_qtlminer&bp=" + breeding_programs_str+'&yrs='+ years_str+'&t='+selectedTraits()+'&e='+experiments_str+'&mm='+mm+'&mmaf='+mmaf;
				
					document.location = url;
				
			}

			function getdownload_tassel(version)
			{
				if (selectedTraits() === '') {
					alert("Please select at least one trait!");
					return false;
				}
			    var mm = $('mm').getValue();
                var mmaf = $('mmaf').getValue();
			    //var subset = $('subset').getValue();
			    var url=php_self + "?function=type2_build_tassel_" + version + "&lines=" + lines_str+'&yrs='+ years_str+'&e='+experiments_str+'&pi='+phenotype_items_str+'&subset='+subset+'&mm='+mm+'&mmaf='+mmaf;
               document.location = url;
			}

            function mrefresh() {
                var mm = $('mm').getValue();
                var mmaf = $('mmaf').getValue();
                var use_line = "yes";
                if (select1_str == "Phenotypes") {
                    load_markers_pheno( mm, mmaf);
                } else if (select1_str == "Locations") {
                    load_markers_loc( mm, mmaf);
                } else if (select1_str == "Lines") {
                    load_markers_lines( mm, mmaf, use_line);
                } else {
                    load_markers( mm, mmaf);
                }
            }

function filterDesc(min_maf, max_missing, max_miss_line) {
  alert("1. Marker allele frequency is calculated for the selected lines.\n2. Markers are removed that have MAF less than " +  min_maf + "% or are missing in more than " + max_missing + "% of the lines.\n3. Lines are removed if they are missing more than " + max_miss_line + "% of the marker data.\nAfter changing the default settings for the filter, select Download to use the new paramaters");
}

function linesRemoved(lineRemovedName) {
  alert("Lines Removed\n" + lineRemovedName);
}
           
