/**
 * Interface Elements for jQuery
 * Droppables
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
 * With the Draggables plugin, Droppable allows you to create drop zones for draggable elements.
 *
 * @name Droppable
 * @cat Plugins/Interface
 * @param Hash options A hash of options
 * @option String accept The class name for draggables to get accepted by the droppable (mandatory)
 * @option String activeclass When an acceptable draggable is moved, the droppable gets this class
 * @option String hoverclass When an acceptable draggable is inside the droppable, the droppable gets
 *                           this class
 * @option String tolerance  Choose from 'pointer', 'intersect', or 'fit'. The pointer options means
 *                           that the pointer must be inside the droppable in order for the draggable
 *                           to be dropped. The intersect option means that the draggable must intersect
 *                           the droppable. The fit option means that the entire draggable must be
 *                           inside the droppable.
 * @option Function onDrop   When an acceptable draggable is dropped on a droppable, this callback is
 *                           called. It passes the draggable DOMElement as a parameter.
 * @option Function onHover  When an acceptable draggable is hovered over a droppable, this callback
 *                           is called. It passes the draggable DOMElement as a parameter.
 * @option Function onOut    When an acceptable draggable leaves a droppable, this callback is called.
 *                           It passes the draggable DOMElement as a parameter.
 * @example                  $('#dropzone1').Droppable(
 *                             {
 *                               accept : 'dropaccept', 
 *                               activeclass: 'dropzoneactive', 
 *                               hoverclass:	'dropzonehover',
 *                               ondrop:	function (drag) {
 *                                              alert(this); //the droppable
 *                                              alert(drag); //the draggable
 *                                        },
 *                               fit: true
 *                             }
 *                           )
 */
jQuery.iDrop={fit:function(r,e,o,g){return r<=jQuery.iDrag.dragged.dragCfg.nx&&r+o>=jQuery.iDrag.dragged.dragCfg.nx+jQuery.iDrag.dragged.dragCfg.oC.w&&e<=jQuery.iDrag.dragged.dragCfg.ny&&e+g>=jQuery.iDrag.dragged.dragCfg.ny+jQuery.iDrag.dragged.dragCfg.oC.h},intersect:function(r,e,o,g){return!(r>jQuery.iDrag.dragged.dragCfg.nx+jQuery.iDrag.dragged.dragCfg.oC.w||r+o<jQuery.iDrag.dragged.dragCfg.nx||e>jQuery.iDrag.dragged.dragCfg.ny+jQuery.iDrag.dragged.dragCfg.oC.h||e+g<jQuery.iDrag.dragged.dragCfg.ny)},pointer:function(r,e,o,g){return r<jQuery.iDrag.dragged.dragCfg.currentPointer.x&&r+o>jQuery.iDrag.dragged.dragCfg.currentPointer.x&&e<jQuery.iDrag.dragged.dragCfg.currentPointer.y&&e+g>jQuery.iDrag.dragged.dragCfg.currentPointer.y},overzone:!1,highlighted:{},count:0,zones:{},highlight:function(r){if(null!=jQuery.iDrag.dragged){var e,o=!(jQuery.iDrop.highlighted={});for(e in jQuery.iDrop.zones)if(null!=jQuery.iDrop.zones[e]){var g=jQuery.iDrop.zones[e].get(0);jQuery(jQuery.iDrag.dragged).is("."+g.dropCfg.a)&&(0==g.dropCfg.m&&(g.dropCfg.p=jQuery.extend(jQuery.iUtil.getPositionLite(g),jQuery.iUtil.getSizeLite(g)),g.dropCfg.m=!0),g.dropCfg.ac&&jQuery.iDrop.zones[e].addClass(g.dropCfg.ac),jQuery.iDrop.highlighted[e]=jQuery.iDrop.zones[e],jQuery.iSort&&g.dropCfg.s&&jQuery.iDrag.dragged.dragCfg.so&&(g.dropCfg.el=jQuery("."+g.dropCfg.a,g),r.style.display="none",jQuery.iSort.measure(g),g.dropCfg.os=jQuery.iSort.serialize(jQuery.attr(g,"id")).hash,r.style.display=r.dragCfg.oD,o=!0),g.dropCfg.onActivate&&g.dropCfg.onActivate.apply(jQuery.iDrop.zones[e].get(0),[jQuery.iDrag.dragged]))}o&&jQuery.iSort.start()}},remeasure:function(){for(i in jQuery.iDrop.highlighted={},jQuery.iDrop.zones)if(null!=jQuery.iDrop.zones[i]){var r=jQuery.iDrop.zones[i].get(0);jQuery(jQuery.iDrag.dragged).is("."+r.dropCfg.a)&&(r.dropCfg.p=jQuery.extend(jQuery.iUtil.getPositionLite(r),jQuery.iUtil.getSizeLite(r)),r.dropCfg.ac&&jQuery.iDrop.zones[i].addClass(r.dropCfg.ac),jQuery.iDrop.highlighted[i]=jQuery.iDrop.zones[i],jQuery.iSort&&r.dropCfg.s&&jQuery.iDrag.dragged.dragCfg.so&&(r.dropCfg.el=jQuery("."+r.dropCfg.a,r),elm.style.display="none",jQuery.iSort.measure(r),elm.style.display=elm.dragCfg.oD))}},checkhover:function(r){if(null!=jQuery.iDrag.dragged){var e,o=jQuery.iDrop.overzone=!1;for(e in jQuery.iDrop.highlighted){var g=jQuery.iDrop.highlighted[e].get(0);0==jQuery.iDrop.overzone&&jQuery.iDrop[g.dropCfg.t](g.dropCfg.p.x,g.dropCfg.p.y,g.dropCfg.p.wb,g.dropCfg.p.hb)?(g.dropCfg.hc&&0==g.dropCfg.h&&jQuery.iDrop.highlighted[e].addClass(g.dropCfg.hc),0==g.dropCfg.h&&g.dropCfg.onHover&&(o=!0),g.dropCfg.h=!0,jQuery.iDrop.overzone=g,jQuery.iSort&&g.dropCfg.s&&jQuery.iDrag.dragged.dragCfg.so&&(jQuery.iSort.helper.get(0).className=g.dropCfg.shc,jQuery.iSort.checkhover(g)),0):1==g.dropCfg.h&&(g.dropCfg.onOut&&g.dropCfg.onOut.apply(g,[r,jQuery.iDrag.helper.get(0).firstChild,g.dropCfg.fx]),g.dropCfg.hc&&jQuery.iDrop.highlighted[e].removeClass(g.dropCfg.hc),g.dropCfg.h=!1)}jQuery.iSort&&!jQuery.iDrop.overzone&&jQuery.iDrag.dragged.so&&(jQuery.iSort.helper.get(0).style.display="none"),o&&jQuery.iDrop.overzone.dropCfg.onHover.apply(jQuery.iDrop.overzone,[r,jQuery.iDrag.helper.get(0).firstChild])}},checkdrop:function(r){var e;for(e in jQuery.iDrop.highlighted){var o=jQuery.iDrop.highlighted[e].get(0);o.dropCfg.ac&&jQuery.iDrop.highlighted[e].removeClass(o.dropCfg.ac),o.dropCfg.hc&&jQuery.iDrop.highlighted[e].removeClass(o.dropCfg.hc),o.dropCfg.s&&(jQuery.iSort.changed[jQuery.iSort.changed.length]=e),o.dropCfg.onDrop&&1==o.dropCfg.h&&(o.dropCfg.h=!1,o.dropCfg.onDrop.apply(o,[r,o.dropCfg.fx])),o.dropCfg.m=!1,o.dropCfg.h=!1}jQuery.iDrop.highlighted={}},destroy:function(){return this.each(function(){this.isDroppable&&(this.dropCfg.s&&(id=jQuery.attr(this,"id"),jQuery.iSort.collected[id]=null,jQuery("."+this.dropCfg.a,this).DraggableDestroy()),jQuery.iDrop.zones["d"+this.idsa]=null,this.isDroppable=!1,this.f=null)})},build:function(r){return this.each(function(){1!=this.isDroppable&&r.accept&&jQuery.iUtil&&jQuery.iDrag&&(this.dropCfg={a:r.accept,ac:r.activeclass||!1,hc:r.hoverclass||!1,shc:r.helperclass||!1,onDrop:r.ondrop||r.onDrop||!1,onHover:r.onHover||r.onhover||!1,onOut:r.onOut||r.onout||!1,onActivate:r.onActivate||!1,t:!r.tolerance||"fit"!=r.tolerance&&"intersect"!=r.tolerance?"pointer":r.tolerance,fx:!!r.fx&&r.fx,m:!1,h:!1},1==r.sortable&&jQuery.iSort&&(id=jQuery.attr(this,"id"),jQuery.iSort.collected[id]=this.dropCfg.a,this.dropCfg.s=!0,r.onChange&&(this.dropCfg.onChange=r.onChange,this.dropCfg.os=jQuery.iSort.serialize(id).hash)),this.isDroppable=!0,this.idsa=parseInt(1e4*Math.random()),jQuery.iDrop.zones["d"+this.idsa]=jQuery(this),jQuery.iDrop.count++)})}};

/**
 * Destroy an existing droppable on a collection of elements
 * 
 * @name DroppableDestroy
 * @descr Destroy a droppable
 * @type jQuery
 * @cat Plugins/Interface
 * @example $('#drag2').DroppableDestroy();
 */

jQuery.fn.extend(
	{
		DroppableDestroy : jQuery.iDrop.destroy,
		Droppable : jQuery.iDrop.build
	}
);

 
/**
 * Recalculate all Droppables
 *
 * @name $.recallDroppables
 * @type jQuery
 * @cat Plugins/Interface
 * @example $.recallDroppable();
 */

jQuery.recallDroppables = jQuery.iDrop.remeasure;