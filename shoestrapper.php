<?PHP
	class siteMaker{

		public $root_name = "";
		public $column_width = "";
		public $background = "";
		public $isUsingBackgroundImg = false;
		public $isUsingHeaderBar = false;
		public $headerNames;
		public $headerPaths;
		public $numberOfItems = 0;
		public $headercontent = "";

		function ask_user_questions(){

			echo "enter filename to store website in: ";
			$this -> root_name = trim(fgets(STDIN));
			echo "\r\n are you going to use a picture for your background? (y/n)";
			$in =trim(strtolower(fgets(STDIN)));

			if($in=="y"){
				$this -> isUsingBackgroundImg = true;
			}else{
				$this -> isUsingBackgroundImg = false;
			};

			echo "\r\n want a header bar? (y/n)";

			$in2 =trim(strtolower(fgets(STDIN)));

			if($in2=="y"){
				$this -> isUsingHeaderBar = true;
			}else{
				$this -> isUsingHeaderBar = false;
			};

			if ($this -> isUsingHeaderBar){
				echo "\r\ngive me a list of menu options seperated by commas:";
				$menuoptions = trim(fgets(STDIN));

				$menuoptionsarray = explode(",", $menuoptions);

				$itemcount = 0;
				
				if(sizeof($menuoptionsarray)>0){

				foreach($menuoptionsarray as $menuitem){

					$this -> headerNames[$itemcount] = trim($menuitem);
					$itemcount++;
				}

				$this -> numberOfItems = $itemcount;
				//echo "itemcount".$itemcount."\r\n";
				}else{echo "no header items entered. \r\n";}
			}
		}

		public function make_root_folder(){
			
			$root_name = $this -> root_name;

			mkdir($root_name, 0777);
			//chmod($root_name, 0777);
			//exec("cd ".$root_name);
			mkdir($root_name."/includes", 0777);
			mkdir($root_name."/images", 0777);
			mkdir($root_name."/content", 0777);
			$header = "<?PHP ?> <html><head><link rel=\"stylesheet\" type=\"text/css\" href=\"includes/mystyle.css\"></head><body>";
			$footer = "</body></html>";
			
			//$userchoice = strtolower(trim(fgets(STDIN)));
			if($this -> isUsingBackgroundImg){
				$images = scandir(dirname(__FILE__));
				$imgcount = 0;
				echo"\r\n\r\nenter the name of the image you would liek to use: \r\n";
				foreach($images as $image){
					echo "".$imgcount." ".$image."\r\n";
					$imgcount++;
				}
				$imgchoice = trim(fgets(STDIN));
				$selected = file_get_contents($imgchoice);
				file_put_contents($this -> root_name."/images/".$imgchoice, $selected);
				$this -> background = "background-image:url('../images/".$imgchoice."')";
			}else{if(!$this -> isUsingBackgroundImg){
			echo "\r\n\r\nwhat color do you want the body background color (enter a hex value): ";
			$bgcolor = trim(fgets(STDIN));
			$this -> background = "background-color:#".$bgcolor;
					}}
			$style =   "hr {color:sienna;}
						p {margin-left:20px;}
						body {".$this -> background.";}";
			$index = "<?PHP include \"includes/header.php\"; 
						include \"content/default.php\";
						include \"includes/footer.php\"; ?>";
			file_put_contents($root_name."/includes/header.php", $header);
			file_put_contents($root_name."/includes/footer.php", $footer);
			file_put_contents($root_name."/includes/mystyle.css", $style);
			file_put_contents($root_name."/index.php", $index);
		}


		public function make_header_div_file(){


			echo "welcome to the make_header_div_file function! \r\n
				 the main column width is: ". $this -> column_width."\r\n
				 the number of header menu items is: ". $this -> numberOfItems. "\r\n ";
				 $width = (100/$this -> numberOfItems);
				 $colWidth = $width*$this->numberOfItems;
				$tinyDivStyle[0] = "width:".$width."%";
				$tinyDivStyle[1] = "font-size:12px";
				$tinyDivStyle[2] = "background-color:#aaaaaa";
				$tinyDivStyle[3] = "position:relative";
				$tinyDivStyle[4] = "color:000000";
				$tinyDivStyle[5] = "float:left";
				//$tinyDivStyle[6] = "padding:2%";


				$envelopeStyle[0] = "width:100%";
				$envelopeStyle[1] = "position:absolute";
				$envelopeStyle[2] = "border-radius:20px";
				$envelopeStyle[3] = "overflow:hidden";
				//$envelopeStyle[4] = "margin:10px";
				//$envelopeStyle[5] = "padding:0px";

				$theContent;

				$theContent = $this -> returnopenDiv("envelope", "left", $envelopeStyle);
				foreach($this -> headerNames as $hname){
					$theContent = $theContent.$this -> returnopenDiv("div_".$hname,"left", $tinyDivStyle);
					$theContent = $theContent."some html text";
					$theContent = $theContent.$this -> returncloseDiv();
				}
				$theContent = $theContent.$this -> returncloseDiv();


				//echo $theContent;

				$this -> headercontent = $theContent;


		

		}


		public function intchartoint($intchar){

			$intval = ord($char) - 47;

			return $intval;



		}

		public function make_content_file(){
			$content = "";
			$divOneStyle[0] = "color:#000000"; 
			$divOneStyle[1] = "font-size:24px";
			$divOneStyle[2] = "width:80%";
			$divOneStyle[3] = "position:relative";
			$divOneStyle[4] = "bottom:-5%";
			$divOneStyle[5] = "left:10%";
			$divOneStyle[6] = "background-color:#ffffff";
			$divOneStyle[7] = "height:100%";
			$divOneStyle[8] = "border-radius:20px";
			$divOneStyle[9] = "padding:15px";
			$divOneStyle[10] = "overflow:hidden";
			$this -> column_width = $divOneStyle[2];
			$mainDiv = $this -> returnopenDiv("defaultDiv", "left", $divOneStyle);
			$content = $content.$mainDiv;
			$content = $content.$this -> headercontent; 
			$endDiv = $this -> returncloseDiv();
			$content = $content.$endDiv;
			$content = $content."<?PHP if(file_exists('/includes/menu.php')){include 'includes/menu.php';} ?>";
			file_put_contents($this -> root_name."/content/default.php", $content);
		}
	  	public function returnopenDiv($name, $align, $style){
  			$openderp = '<div name = "'.$name.'" align = "'.$align;
  			if($style){ $openderp = $openderp . '" style ="';
  				foreach($style as $styl){
  					$openderp = $openderp . $styl;
  					$openderp = $openderp .  "; ";
  				}
  			}
  			$openderp = $openderp . '">';
  			//file_put_contents("contents_of_openderp.txt", $openderp);
  			return $openderp;
  		}
	  	public function returncloseDiv(){
  			$closederp = "</div>";
  			//echo "".$closed;
  			//file_put_contents("contents_of_closedderp.txt", $closederp);
  			return $closederp;
  		}
	}
	$cats = new siteMaker();
	$cats -> ask_user_questions();
	$cats -> make_root_folder();
	$cats -> make_header_div_file();
	$cats -> make_content_file();
	
?>