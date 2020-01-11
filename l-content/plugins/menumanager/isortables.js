/**
 * Interface Elements for jQuery
 * Sortables
 * 
 * http://interface.eyecon.ro
 * 
 * Copyright (c) 2006 Stefan Petre
 * Dual licensed under the MIT (MIT-LICENSE.txt) 
 * and GPL (GPL-LICENSE.txt) licenses.
 *   
 *
 */

/**
 * Allows you to resort elements within a container by dragging and dropping. Requires
 * the Draggables and Droppables plugins. The container and each item inside the container
 * must have an ID. Sortables are especially useful for lists.
 * 
 * @see Plugins/Interface/Draggable
 * @see Plugins/Interface/Droppable
 * @author Stefan Petre
 * @name Sortable
 * @cat Plugins/Interface
 * @param Hash options        A hash of options
 * @option String accept      The class name for items inside the container (mandatory)
 * @option String activeclass The class for the container when one of its items has started to move
 * @option String hoverclass  The class for the container when an acceptable item is inside it
 * @option String helperclass The helper is used to point to the place where the item will be 
 *                            moved. This is the class for the helper.
 * @option Float opacity      Opacity (between 0 and 1) of the item while being dragged
 * @option Boolean ghosting   When true, the sortable is ghosted when dragged
 * @option String tolerance   Either 'pointer', 'intersect', or 'fit'. See Droppable for more details
 * @option Boolean fit        When true, sortable must be inside the container in order to drop
 * @option Integer fx         Duration for the effect applied to the sortable
 * @option Function onchange  Callback that gets called when the sortable list changed. It takes
 *                            an array of serialized elements
 * @option Boolean floats     True if the sorted elements are floated
 * @option String containment Use 'parent' to constrain the drag to the container
 * @option String axis        Use 'horizontally' or 'vertically' to constrain dragging to an axis
 * @option String handle      The jQuery selector that indicates the draggable handle
 * @option DOMElement handle  The node that indicates the draggable handle
 * @option Function onHover   Callback that is called when an acceptable item is dragged over the
 *                            container. Gets the hovering DOMElement as a parameter
 * @option Function onOut     Callback that is called when an acceptable item leaves the container.
 *                            Gets the leaving DOMElement as a parameter
 * @option Object cursorAt    The mouse cursor will be moved to the offset on the dragged item
 *                            indicated by the object, which takes "top", "bottom", "left", and
 *                            "right" keys
 * @option Function onStart   Callback function triggered when the dragging starts
 * @option Function onStop    Callback function triggered when the dragging stops
 * @example                   $('ul').Sortable(
 *                            	{
 *                            		accept : 'sortableitem',
 *                            		activeclass : 'sortableactive',
 *                             		hoverclass : 'sortablehover',
 *                             		helperclass : 'sorthelper',
 *                             		opacity: 	0.5,
 *                             		fit :	false
 *                             	}
 *                             )
 */

jQuery.iSort={changed:[],collected:{},helper:!1,inFrontOf:null,start:function(){var e,r,t,o;null!=jQuery.iDrag.dragged&&(jQuery.iSort.helper.get(0).className=jQuery.iDrag.dragged.dragCfg.hpc,(e=jQuery.iSort.helper.get(0).style).display="block",jQuery.iSort.helper.oC=jQuery.extend(jQuery.iUtil.getPosition(jQuery.iSort.helper.get(0)),jQuery.iUtil.getSize(jQuery.iSort.helper.get(0))),e.width=jQuery.iDrag.dragged.dragCfg.oC.wb+"px",e.height=jQuery.iDrag.dragged.dragCfg.oC.hb+"px",r=jQuery.iUtil.getMargins(jQuery.iDrag.dragged),e.marginTop=r.t,e.marginRight=r.r,e.marginBottom=r.b,e.marginLeft=r.l,1==jQuery.iDrag.dragged.dragCfg.ghosting&&((o=(t=jQuery.iDrag.dragged.cloneNode(!0)).style).marginTop="0px",o.marginRight="0px",o.marginBottom="0px",o.marginLeft="0px",o.display="block",jQuery.iSort.helper.empty().append(t)),jQuery(jQuery.iDrag.dragged).after(jQuery.iSort.helper.get(0)),jQuery.iDrag.dragged.style.display="none")},check:function(e){!e.dragCfg.so&&jQuery.iDrop.overzone.sortable&&(e.dragCfg.onStop&&e.dragCfg.onStop.apply(dragged),jQuery(e).css("position",e.dragCfg.initialPosition||e.dragCfg.oP),jQuery(e).DraggableDestroy(),jQuery(jQuery.iDrop.overzone).SortableAddItem(e)),jQuery.iSort.helper.removeClass(e.dragCfg.hpc).html("&nbsp;"),jQuery.iSort.inFrontOf=null,jQuery.iSort.helper.get(0).style.display="none",jQuery.iSort.helper.after(e),0<e.dragCfg.fx&&jQuery(e).fadeIn(e.dragCfg.fx),jQuery("body").append(jQuery.iSort.helper.get(0));for(var r=[],t=!1,o=0;o<jQuery.iSort.changed.length;o++){var i=jQuery.iDrop.zones[jQuery.iSort.changed[o]].get(0),a=jQuery.attr(i,"id"),n=jQuery.iSort.serialize(a);i.dropCfg.os!=n.hash&&(i.dropCfg.os=n.hash,0==t&&i.dropCfg.onChange&&(t=i.dropCfg.onChange),n.id=a,r[r.length]=n)}jQuery.iSort.changed=[],0!=t&&0<r.length&&t(r)},checkhover:function(e,r){if(jQuery.iDrag.dragged){var t=!1,o=0;if(0<e.dropCfg.el.size())for(o=e.dropCfg.el.size();0<o;o--)if(e.dropCfg.el.get(o-1)!=jQuery.iDrag.dragged)if(e.sortCfg.floats)e.dropCfg.el.get(o-1).pos.x+e.dropCfg.el.get(o-1).pos.wb/2>jQuery.iDrag.dragged.dragCfg.nx&&e.dropCfg.el.get(o-1).pos.y+e.dropCfg.el.get(o-1).pos.hb/2>jQuery.iDrag.dragged.dragCfg.ny&&(t=e.dropCfg.el.get(o-1));else{if(!(e.dropCfg.el.get(o-1).pos.y+e.dropCfg.el.get(o-1).pos.hb/2>jQuery.iDrag.dragged.dragCfg.ny))break;t=e.dropCfg.el.get(o-1)}t&&jQuery.iSort.inFrontOf!=t?(jQuery.iSort.inFrontOf=t,jQuery(t).before(jQuery.iSort.helper.get(0))):t||null==jQuery.iSort.inFrontOf&&jQuery.iSort.helper.get(0).parentNode==e||(jQuery.iSort.inFrontOf=null,jQuery(e).append(jQuery.iSort.helper.get(0))),jQuery.iSort.helper.get(0).style.display="block"}},measure:function(e){null!=jQuery.iDrag.dragged&&e.dropCfg.el.each(function(){this.pos=jQuery.extend(jQuery.iUtil.getSizeLite(this),jQuery.iUtil.getPositionLite(this))})},serialize:function(e){var r,t="",o={};if(e)if(jQuery.iSort.collected[e])o[e]=[],jQuery("#"+e+" ."+jQuery.iSort.collected[e]).each(function(){0<t.length&&(t+="&"),t+=e+"[]="+jQuery.attr(this,"id"),o[e][o[e].length]=jQuery.attr(this,"id")});else for(a in e)jQuery.iSort.collected[e[a]]&&(o[e[a]]=[],jQuery("#"+e[a]+" ."+jQuery.iSort.collected[e[a]]).each(function(){0<t.length&&(t+="&"),t+=e[a]+"[]="+jQuery.attr(this,"id"),o[e[a]][o[e[a]].length]=jQuery.attr(this,"id")}));else for(r in jQuery.iSort.collected)o[r]=[],jQuery("#"+r+" ."+jQuery.iSort.collected[r]).each(function(){0<t.length&&(t+="&"),t+=r+"[]="+jQuery.attr(this,"id"),o[r][o[r].length]=jQuery.attr(this,"id")});return{hash:t,o:o}},addItem:function(e){if(e.childNodes)return this.each(function(){this.sortCfg&&jQuery(e).is("."+this.sortCfg.accept)||jQuery(e).addClass(this.sortCfg.accept),jQuery(e).Draggable(this.sortCfg.dragCfg)})},destroy:function(){return this.each(function(){jQuery("."+this.sortCfg.accept).DraggableDestroy(),jQuery(this).DroppableDestroy(),this.sortCfg=null,this.isSortable=null})},build:function(r){if(r.accept&&jQuery.iUtil&&jQuery.iDrag&&jQuery.iDrop)return jQuery.iSort.helper||(jQuery("body",document).append('<div id="sortHelper">&nbsp;</div>'),jQuery.iSort.helper=jQuery("#sortHelper"),jQuery.iSort.helper.get(0).style.display="none"),this.Droppable({accept:r.accept,activeclass:!!r.activeclass&&r.activeclass,hoverclass:!!r.hoverclass&&r.hoverclass,helperclass:!!r.helperclass&&r.helperclass,onHover:r.onHover||r.onhover,onOut:r.onOut||r.onout,sortable:!0,onChange:r.onChange||r.onchange,fx:!!r.fx&&r.fx,ghosting:!!r.ghosting,tolerance:r.tolerance?r.tolerance:"intersect"}),this.each(function(){var e={revert:!!r.revert,zindex:3e3,opacity:!!r.opacity&&parseFloat(r.opacity),hpc:!!r.helperclass&&r.helperclass,fx:!!r.fx&&r.fx,so:!0,ghosting:!!r.ghosting,handle:r.handle?r.handle:null,containment:r.containment?r.containment:null,onStart:!(!r.onStart||r.onStart.constructor!=Function)&&r.onStart,onDrag:!(!r.onDrag||r.onDrag.constructor!=Function)&&r.onDrag,onStop:!(!r.onStop||r.onStop.constructor!=Function)&&r.onStop,axis:!!/vertically|horizontally/.test(r.axis)&&r.axis,snapDistance:!!r.snapDistance&&(parseInt(r.snapDistance)||0),cursorAt:!!r.cursorAt&&r.cursorAt};jQuery("."+r.accept,this).Draggable(e),this.isSortable=!0,this.sortCfg={accept:r.accept,revert:!!r.revert,zindex:3e3,opacity:!!r.opacity&&parseFloat(r.opacity),hpc:!!r.helperclass&&r.helperclass,fx:!!r.fx&&r.fx,so:!0,ghosting:!!r.ghosting,handle:r.handle?r.handle:null,containment:r.containment?r.containment:null,floats:!!r.floats,dragCfg:e}})}};

jQuery.fn.extend({
	Sortable : jQuery.iSort.build,
	/**
	 * A new item can be added to a sortable by adding it to the DOM and then adding it via
	 * SortableAddItem. 
	 *
	 * @name SortableAddItem
	 * @param DOMElement elem A DOM Element to add to the sortable list
	 * @example $('#sortable1').append('<li id="newitem">new item</li>')
	 *                         .SortableAddItem($("#new_item")[0])
	 * @type jQuery
	 * @cat Plugins/Interface
	 */
	SortableAddItem : jQuery.iSort.addItem,
	/**
	 * Destroy a sortable
	 *
	 * @name SortableDestroy
	 * @example $('#sortable1').SortableDestroy();
	 * @type jQuery
	 * @cat Plugins/Interface
	 */
	SortableDestroy: jQuery.iSort.destroy
});

/**
 * This function returns the hash and an object (can be used as arguments for $.post) for every 
 * sortable in the page or specific sortables. The hash is based on the 'id' attributes of 
 * container and items.
 *
 * @params String sortable The id of the sortable to serialize
 * @name $.SortSerialize
 * @type String
 * @cat Plugins/Interface
 */
jQuery.SortSerialize = jQuery.iSort.serialize;