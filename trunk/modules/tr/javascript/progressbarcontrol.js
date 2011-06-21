/**
* @name ProgressbarControl
* @version 1.0
* @author Bjorn BRala
* @copyright (c) 2008 SWIS BV - www.geostart.nl
* @fileoverview Creates a progressbar control for usage in google maps.<br>
<br>Usage:
<br>progressbar = new ProgressbarControl(oMap, opt_opts);
<br>progressbar.start(500); // Amount of operations, unhides the control
<br>progressbar.updateLoader(step); // Add amount of operations just done
<br>progressbar.remove(); // Hide the control.
<br>   
<br>opt_opts: Object containing options:
<br>               {Number} [width=176] Width of the control
<br>               {String} [loadstring=Loading...] String displayed when first starting the control
*/

/**
* @name ProgressbarOptions
* @class This class represents optional arguments to {@link ProgressbarControl}, 
* @property {Number} [width=176] Specifies, in pixels, the width of the progress bar.
* @property {String} [loadstring=Loading...] Specifies the string displayed when first starting the control. Before any update!
*/




/**
*    Custom progress control.
*    Possibly extendable with other styles later on?
*   @private
*   @return GControl object
**/    
function ProgressbarMapControl(map, width) { 
  this.map_ = map; 
  this.width_ = width; 
}


/**
*   @private
**/
ProgressbarMapControl.prototype = new GControl(true, false);
/**
*   @private
*	@desc Initilizes the GControl. Created the HTML and styles.
*	@return Returns container div.
**/
ProgressbarMapControl.prototype.initialize = function () {
  var container_ = document.createElement("div");
  container_.innerHTML         = "<div style='position:absolute;width:100%;border:5px;text-align:center;vertical-align:bottom;' id='geo_progress_text'></div><div style='background-color:green;height:100%;' id='geo_progress'></div>";
  container_.id                 = "geo_progress_container";
  container_.style.display       = "none";
  container_.style.width       = this.width_ + "px";
  container_.style.fontSize    = "0.8em";
  container_.style.height        = "1.3em";
  container_.style.border      = "1px solid #555"; 
  container_.style.backgroundColor = "white";
  container_.style.textAlign     = "left";
  this.map_.getContainer().appendChild(container_);            

  return container_;
};

/**
*   @private 
*   @desc Return the default position for the control
*   @return GControlPosition
**/
ProgressbarMapControl.prototype.getDefaultPosition = function () {
  return new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(30, 56));
};   




/**
*	@contructor
*	@param {GMap2}  Map object
*	@param  {ProgressbarOptions} opt_opts
*   Part of GeoStart (www.geostart.nl)
*   Author: Bjorn Brala SWIS BV
**/
function ProgressbarControl(map, opt_opts) {
  /**
  *   @desc  Init the progress bar, Create a Control on the map.
  *    Loader:     geo_progress_container
  *    Info:        geo_progress
  *   @param {GMap2} GMap2 object
  **/    
  this.options_ = opt_opts == null ? {} : opt_opts;


  this.width_ = this.options_.width == null ? 176 : this.options_.width;
  this.loadstring_ = this.options_.loadstring == null ? 'Loading...' : this.options_.loadstring;                             // String for when loading ( before counter )        

  this.control_ = new ProgressbarMapControl(map, this.width_);          /* Control object reference */

  this.map_ = map;                                                      /* GMap2 reference  */
  this.map_.addControl(this.control_);                                  /* Load control into map  */
  this.div_ = document.getElementById('geo_progress');                  /* progress DIV  */
  this.text_ = document.getElementById('geo_progress_text');            /* progress text DIV  */
  this.container_ = document.getElementById('geo_progress_container');  /* progress container  */

  this.operations_ = 0;
  this.current_ = 0;
}

/**
*    @desc Start the progress bar. Argumnent is the amount of operations the full bar will represent.
*    @param {int} operations Counter for the amount of operations that will be executed.
**/
ProgressbarControl.prototype.start = function (operations) {
  this.div_.style.width = '0%'; 
  this.operations_ = operations || 0;
  this.current_ = 0;
  this.text_.style.color = "#111";
  this.text_.innerHTML = this.loadstring_;
  this.container_.style.display = "block";
};


/**
*   @desc  Update the progress. Adds Step amount of operations to the bar.
*   @param {int} step Add number of operations to progress.
**/
ProgressbarControl.prototype.updateLoader = function (step) {
  this.current_ += step;
  if (this.current_ > 0) {
    var percentage_ = Math.ceil((this.current_ / this.operations_) * 100);
    if (percentage_ > 100) { 
      percentage_ = 100; 
    }
    this.div_.style.width = percentage_ + '%'; 
    this.text_.innerHTML = this.current_ + ' / ' + this.operations_;
  } 
};

/**
*    @desc Remove control. Well, hide it actually, since the call to create a new one when its needed again would create to much overhead.
**/
ProgressbarControl.prototype.remove = function () {
  this.container_.style.display = 'none';
};