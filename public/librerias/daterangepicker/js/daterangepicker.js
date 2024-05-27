!function(t,e){if("function"==typeof define&&define.amd)define(["moment","jquery"],function(t,a){return a.fn||(a.fn={}),"function"!=typeof t&&t.hasOwnProperty("default")&&(t=t.default),e(t,a)});else if("object"==typeof module&&module.exports){var a="undefined"!=typeof window?window.jQuery:void 0;a||(a=require("jquery")).fn||(a.fn={});var i="undefined"!=typeof window&&void 0!==window.moment?window.moment:require("moment");module.exports=e(i,a)}else t.daterangepicker=e(t.moment,t.jQuery)}("undefined"!=typeof window?window:this,function(t,e){var a=function(a,i,s){if(this.parentEl="body",this.element=e(a),this.startDate=t().startOf("day"),this.endDate=t().endOf("day"),this.minDate=!1,this.maxDate=!1,this.maxSpan=!1,this.autoApply=!1,this.singleDatePicker=!1,this.showDropdowns=!1,this.minYear=t().subtract(100,"year").format("YYYY"),this.maxYear=t().add(100,"year").format("YYYY"),this.showWeekNumbers=!1,this.showISOWeekNumbers=!1,this.showCustomRangeLabel=!0,this.timePicker=!1,this.timePicker24Hour=!1,this.timePickerIncrement=1,this.timePickerSeconds=!1,this.linkedCalendars=!0,this.autoUpdateInput=!0,this.alwaysShowCalendars=!1,this.ranges={},this.opens="right",this.element.hasClass("pull-right")&&(this.opens="left"),this.drops="down",this.element.hasClass("dropup")&&(this.drops="up"),this.buttonClasses="btn btn-sm",this.applyButtonClasses="btn-primary",this.cancelButtonClasses="btn-default",this.locale={direction:"ltr",format:t.localeData().longDateFormat("L"),separator:" - ",applyLabel:"Apply",cancelLabel:"Cancel",weekLabel:"W",customRangeLabel:"Custom Range",daysOfWeek:t.weekdaysMin(),monthNames:t.monthsShort(),firstDay:t.localeData().firstDayOfWeek()},this.callback=function(){},this.isShowing=!1,this.leftCalendar={},this.rightCalendar={},("object"!=typeof i||null===i)&&(i={}),"string"==typeof(i=e.extend(this.element.data(),i)).template||i.template instanceof e||(i.template='<div class="daterangepicker"><div class="ranges"></div><div class="drp-calendar left"><div class="calendar-table"></div><div class="calendar-time"></div></div><div class="drp-calendar right"><div class="calendar-table"></div><div class="calendar-time"></div></div><div class="drp-buttons"><span class="drp-selected"></span><button class="cancelBtn" type="button"></button><button class="applyBtn" disabled="disabled" type="button"></button> </div></div>'),this.parentEl=i.parentEl&&e(i.parentEl).length?e(i.parentEl):e(this.parentEl),this.container=e(i.template).appendTo(this.parentEl),"object"==typeof i.locale&&("string"==typeof i.locale.direction&&(this.locale.direction=i.locale.direction),"string"==typeof i.locale.format&&(this.locale.format=i.locale.format),"string"==typeof i.locale.separator&&(this.locale.separator=i.locale.separator),"object"==typeof i.locale.daysOfWeek&&(this.locale.daysOfWeek=i.locale.daysOfWeek.slice()),"object"==typeof i.locale.monthNames&&(this.locale.monthNames=i.locale.monthNames.slice()),"number"==typeof i.locale.firstDay&&(this.locale.firstDay=i.locale.firstDay),"string"==typeof i.locale.applyLabel&&(this.locale.applyLabel=i.locale.applyLabel),"string"==typeof i.locale.cancelLabel&&(this.locale.cancelLabel=i.locale.cancelLabel),"string"==typeof i.locale.weekLabel&&(this.locale.weekLabel=i.locale.weekLabel),"string"==typeof i.locale.customRangeLabel)){var n,r,o,h=document.createElement("textarea");h.innerHTML=i.locale.customRangeLabel;var l=h.value;this.locale.customRangeLabel=l}if(this.container.addClass(this.locale.direction),"string"==typeof i.startDate&&(this.startDate=t(i.startDate,this.locale.format)),"string"==typeof i.endDate&&(this.endDate=t(i.endDate,this.locale.format)),"string"==typeof i.minDate&&(this.minDate=t(i.minDate,this.locale.format)),"string"==typeof i.maxDate&&(this.maxDate=t(i.maxDate,this.locale.format)),"object"==typeof i.startDate&&(this.startDate=t(i.startDate)),"object"==typeof i.endDate&&(this.endDate=t(i.endDate)),"object"==typeof i.minDate&&(this.minDate=t(i.minDate)),"object"==typeof i.maxDate&&(this.maxDate=t(i.maxDate)),this.minDate&&this.startDate.isBefore(this.minDate)&&(this.startDate=this.minDate.clone()),this.maxDate&&this.endDate.isAfter(this.maxDate)&&(this.endDate=this.maxDate.clone()),"string"==typeof i.applyButtonClasses&&(this.applyButtonClasses=i.applyButtonClasses),"string"==typeof i.applyClass&&(this.applyButtonClasses=i.applyClass),"string"==typeof i.cancelButtonClasses&&(this.cancelButtonClasses=i.cancelButtonClasses),"string"==typeof i.cancelClass&&(this.cancelButtonClasses=i.cancelClass),"object"==typeof i.maxSpan&&(this.maxSpan=i.maxSpan),"object"==typeof i.dateLimit&&(this.maxSpan=i.dateLimit),"string"==typeof i.opens&&(this.opens=i.opens),"string"==typeof i.drops&&(this.drops=i.drops),"boolean"==typeof i.showWeekNumbers&&(this.showWeekNumbers=i.showWeekNumbers),"boolean"==typeof i.showISOWeekNumbers&&(this.showISOWeekNumbers=i.showISOWeekNumbers),"string"==typeof i.buttonClasses&&(this.buttonClasses=i.buttonClasses),"object"==typeof i.buttonClasses&&(this.buttonClasses=i.buttonClasses.join(" ")),"boolean"==typeof i.showDropdowns&&(this.showDropdowns=i.showDropdowns),"number"==typeof i.minYear&&(this.minYear=i.minYear),"number"==typeof i.maxYear&&(this.maxYear=i.maxYear),"boolean"==typeof i.showCustomRangeLabel&&(this.showCustomRangeLabel=i.showCustomRangeLabel),"boolean"==typeof i.singleDatePicker&&(this.singleDatePicker=i.singleDatePicker,this.singleDatePicker&&(this.endDate=this.startDate.clone())),"boolean"==typeof i.timePicker&&(this.timePicker=i.timePicker),"boolean"==typeof i.timePickerSeconds&&(this.timePickerSeconds=i.timePickerSeconds),"number"==typeof i.timePickerIncrement&&(this.timePickerIncrement=i.timePickerIncrement),"boolean"==typeof i.timePicker24Hour&&(this.timePicker24Hour=i.timePicker24Hour),"boolean"==typeof i.autoApply&&(this.autoApply=i.autoApply),"boolean"==typeof i.autoUpdateInput&&(this.autoUpdateInput=i.autoUpdateInput),"boolean"==typeof i.linkedCalendars&&(this.linkedCalendars=i.linkedCalendars),"function"==typeof i.isInvalidDate&&(this.isInvalidDate=i.isInvalidDate),"function"==typeof i.isCustomDate&&(this.isCustomDate=i.isCustomDate),"boolean"==typeof i.alwaysShowCalendars&&(this.alwaysShowCalendars=i.alwaysShowCalendars),0!=this.locale.firstDay)for(var c=this.locale.firstDay;c>0;)this.locale.daysOfWeek.push(this.locale.daysOfWeek.shift()),c--;if(void 0===i.startDate&&void 0===i.endDate&&e(this.element).is(":text")){var d=e(this.element).val(),m=d.split(this.locale.separator);n=r=null,2==m.length?(n=t(m[0],this.locale.format),r=t(m[1],this.locale.format)):this.singleDatePicker&&""!==d&&(n=t(d,this.locale.format),r=t(d,this.locale.format)),null!==n&&null!==r&&(this.setStartDate(n),this.setEndDate(r))}if("object"==typeof i.ranges){for(o in i.ranges){n="string"==typeof i.ranges[o][0]?t(i.ranges[o][0],this.locale.format):t(i.ranges[o][0]),r="string"==typeof i.ranges[o][1]?t(i.ranges[o][1],this.locale.format):t(i.ranges[o][1]),this.minDate&&n.isBefore(this.minDate)&&(n=this.minDate.clone());var f=this.maxDate;if(this.maxSpan&&f&&n.clone().add(this.maxSpan).isAfter(f)&&(f=n.clone().add(this.maxSpan)),f&&r.isAfter(f)&&(r=f.clone()),!(this.minDate&&r.isBefore(this.minDate,this.timepicker?"minute":"day")||f&&n.isAfter(f,this.timepicker?"minute":"day"))){var h=document.createElement("textarea");h.innerHTML=o;var l=h.value;this.ranges[l]=[n,r]}}var p="<ul>";for(o in this.ranges)p+='<li data-range-key="'+o+'">'+o+"</li>";this.showCustomRangeLabel&&(p+='<li data-range-key="'+this.locale.customRangeLabel+'">'+this.locale.customRangeLabel+"</li>"),p+="</ul>",this.container.find(".ranges").prepend(p)}"function"==typeof s&&(this.callback=s),this.timePicker||(this.startDate=this.startDate.startOf("day"),this.endDate=this.endDate.endOf("day"),this.container.find(".calendar-time").hide()),this.timePicker&&this.autoApply&&(this.autoApply=!1),this.autoApply&&this.container.addClass("auto-apply"),"object"==typeof i.ranges&&this.container.addClass("show-ranges"),this.singleDatePicker&&(this.container.addClass("single"),this.container.find(".drp-calendar.left").addClass("single"),this.container.find(".drp-calendar.left").show(),this.container.find(".drp-calendar.right").hide(),!this.timePicker&&this.autoApply&&this.container.addClass("auto-apply")),(void 0===i.ranges&&!this.singleDatePicker||this.alwaysShowCalendars)&&this.container.addClass("show-calendar"),this.container.addClass("opens"+this.opens),this.container.find(".applyBtn, .cancelBtn").addClass(this.buttonClasses),this.applyButtonClasses.length&&this.container.find(".applyBtn").addClass(this.applyButtonClasses),this.cancelButtonClasses.length&&this.container.find(".cancelBtn").addClass(this.cancelButtonClasses),this.container.find(".applyBtn").html(this.locale.applyLabel),this.container.find(".cancelBtn").html(this.locale.cancelLabel),this.container.find(".drp-calendar").on("click.daterangepicker",".prev",e.proxy(this.clickPrev,this)).on("click.daterangepicker",".next",e.proxy(this.clickNext,this)).on("mousedown.daterangepicker","td.available",e.proxy(this.clickDate,this)).on("mouseenter.daterangepicker","td.available",e.proxy(this.hoverDate,this)).on("change.daterangepicker","select.yearselect",e.proxy(this.monthOrYearChanged,this)).on("change.daterangepicker","select.monthselect",e.proxy(this.monthOrYearChanged,this)).on("change.daterangepicker","select.hourselect,select.minuteselect,select.secondselect,select.ampmselect",e.proxy(this.timeChanged,this)),this.container.find(".ranges").on("click.daterangepicker","li",e.proxy(this.clickRange,this)),this.container.find(".drp-buttons").on("click.daterangepicker","button.applyBtn",e.proxy(this.clickApply,this)).on("click.daterangepicker","button.cancelBtn",e.proxy(this.clickCancel,this)),this.element.is("input")||this.element.is("button")?this.element.on({"click.daterangepicker":e.proxy(this.show,this),"focus.daterangepicker":e.proxy(this.show,this),"keyup.daterangepicker":e.proxy(this.elementChanged,this),"keydown.daterangepicker":e.proxy(this.keydown,this)}):(this.element.on("click.daterangepicker",e.proxy(this.toggle,this)),this.element.on("keydown.daterangepicker",e.proxy(this.toggle,this))),this.updateElement()};return a.prototype={constructor:a,setStartDate:function(e){"string"==typeof e&&(this.startDate=t(e,this.locale.format)),"object"==typeof e&&(this.startDate=t(e)),this.timePicker||(this.startDate=this.startDate.startOf("day")),this.timePicker&&this.timePickerIncrement&&this.startDate.minute(Math.round(this.startDate.minute()/this.timePickerIncrement)*this.timePickerIncrement),this.minDate&&this.startDate.isBefore(this.minDate)&&(this.startDate=this.minDate.clone(),this.timePicker&&this.timePickerIncrement&&this.startDate.minute(Math.round(this.startDate.minute()/this.timePickerIncrement)*this.timePickerIncrement)),this.maxDate&&this.startDate.isAfter(this.maxDate)&&(this.startDate=this.maxDate.clone(),this.timePicker&&this.timePickerIncrement&&this.startDate.minute(Math.floor(this.startDate.minute()/this.timePickerIncrement)*this.timePickerIncrement)),this.isShowing||this.updateElement(),this.updateMonthsInView()},setEndDate:function(e){"string"==typeof e&&(this.endDate=t(e,this.locale.format)),"object"==typeof e&&(this.endDate=t(e)),this.timePicker||(this.endDate=this.endDate.endOf("day")),this.timePicker&&this.timePickerIncrement&&this.endDate.minute(Math.round(this.endDate.minute()/this.timePickerIncrement)*this.timePickerIncrement),this.endDate.isBefore(this.startDate)&&(this.endDate=this.startDate.clone()),this.maxDate&&this.endDate.isAfter(this.maxDate)&&(this.endDate=this.maxDate.clone()),this.maxSpan&&this.startDate.clone().add(this.maxSpan).isBefore(this.endDate)&&(this.endDate=this.startDate.clone().add(this.maxSpan)),this.previousRightTime=this.endDate.clone(),this.container.find(".drp-selected").html(this.startDate.format(this.locale.format)+this.locale.separator+this.endDate.format(this.locale.format)),this.isShowing||this.updateElement(),this.updateMonthsInView()},isInvalidDate:function(){return!1},isCustomDate:function(){return!1},updateView:function(){this.timePicker&&(this.renderTimePicker("left"),this.renderTimePicker("right"),this.endDate?this.container.find(".right .calendar-time select").prop("disabled",!1).removeClass("disabled"):this.container.find(".right .calendar-time select").prop("disabled",!0).addClass("disabled")),this.endDate&&this.container.find(".drp-selected").html(this.startDate.format(this.locale.format)+this.locale.separator+this.endDate.format(this.locale.format)),this.updateMonthsInView(),this.updateCalendars(),this.updateFormInputs()},updateMonthsInView:function(){if(this.endDate){if(!this.singleDatePicker&&this.leftCalendar.month&&this.rightCalendar.month&&(this.startDate.format("YYYY-MM")==this.leftCalendar.month.format("YYYY-MM")||this.startDate.format("YYYY-MM")==this.rightCalendar.month.format("YYYY-MM"))&&(this.endDate.format("YYYY-MM")==this.leftCalendar.month.format("YYYY-MM")||this.endDate.format("YYYY-MM")==this.rightCalendar.month.format("YYYY-MM")))return;this.leftCalendar.month=this.startDate.clone().date(2),this.linkedCalendars||this.endDate.month()==this.startDate.month()&&this.endDate.year()==this.startDate.year()?this.rightCalendar.month=this.startDate.clone().date(2).add(1,"month"):this.rightCalendar.month=this.endDate.clone().date(2)}else this.leftCalendar.month.format("YYYY-MM")!=this.startDate.format("YYYY-MM")&&this.rightCalendar.month.format("YYYY-MM")!=this.startDate.format("YYYY-MM")&&(this.leftCalendar.month=this.startDate.clone().date(2),this.rightCalendar.month=this.startDate.clone().date(2).add(1,"month"));this.maxDate&&this.linkedCalendars&&!this.singleDatePicker&&this.rightCalendar.month>this.maxDate&&(this.rightCalendar.month=this.maxDate.clone().date(2),this.leftCalendar.month=this.maxDate.clone().date(2).subtract(1,"month"))},updateCalendars:function(){if(this.timePicker){var t,e,a;if(this.endDate){if(t=parseInt(this.container.find(".left .hourselect").val(),10),isNaN(e=parseInt(this.container.find(".left .minuteselect").val(),10))&&(e=parseInt(this.container.find(".left .minuteselect option:last").val(),10)),a=this.timePickerSeconds?parseInt(this.container.find(".left .secondselect").val(),10):0,!this.timePicker24Hour){var i=this.container.find(".left .ampmselect").val();"PM"===i&&t<12&&(t+=12),"AM"===i&&12===t&&(t=0)}}else if(t=parseInt(this.container.find(".right .hourselect").val(),10),isNaN(e=parseInt(this.container.find(".right .minuteselect").val(),10))&&(e=parseInt(this.container.find(".right .minuteselect option:last").val(),10)),a=this.timePickerSeconds?parseInt(this.container.find(".right .secondselect").val(),10):0,!this.timePicker24Hour){var i=this.container.find(".right .ampmselect").val();"PM"===i&&t<12&&(t+=12),"AM"===i&&12===t&&(t=0)}this.leftCalendar.month.hour(t).minute(e).second(a),this.rightCalendar.month.hour(t).minute(e).second(a)}this.renderCalendar("left"),this.renderCalendar("right"),this.container.find(".ranges li").removeClass("active"),null!=this.endDate&&this.calculateChosenLabel()},renderCalendar:function(a){var i,s,n="left"==a?this.leftCalendar:this.rightCalendar,r=n.month.month(),o=n.month.year(),h=n.month.hour(),l=n.month.minute(),c=n.month.second(),d=t([o,r]).daysInMonth(),m=t([o,r,1]),f=t([o,r,d]),p=t(m).subtract(1,"month").month(),u=t(m).subtract(1,"month").year(),D=t([u,p]).daysInMonth(),g=m.day(),n=[];n.firstDay=m,n.lastDay=f;for(var y=0;y<6;y++)n[y]=[];var k=D-g+this.locale.firstDay+1;k>D&&(k-=7),g==this.locale.firstDay&&(k=D-6);for(var v=t([u,p,k,12,l,c]),y=0,i=0,s=0;y<42;y++,i++,v=t(v).add(24,"hour"))y>0&&i%7==0&&(i=0,s++),n[s][i]=v.clone().hour(h).minute(l).second(c),v.hour(12),this.minDate&&n[s][i].format("YYYY-MM-DD")==this.minDate.format("YYYY-MM-DD")&&n[s][i].isBefore(this.minDate)&&"left"==a&&(n[s][i]=this.minDate.clone()),this.maxDate&&n[s][i].format("YYYY-MM-DD")==this.maxDate.format("YYYY-MM-DD")&&n[s][i].isAfter(this.maxDate)&&"right"==a&&(n[s][i]=this.maxDate.clone());"left"==a?this.leftCalendar.calendar=n:this.rightCalendar.calendar=n;var b="left"==a?this.minDate:this.startDate,C=this.maxDate;"left"==a?this.startDate:this.endDate,this.locale.direction;var Y='<table class="table-condensed">';Y+="<thead>",Y+="<tr>",(this.showWeekNumbers||this.showISOWeekNumbers)&&(Y+="<th></th>"),(!b||b.isBefore(n.firstDay))&&(!this.linkedCalendars||"left"==a)?Y+='<th class="prev available"><span></span></th>':Y+="<th></th>";var _=this.locale.monthNames[n[1][1].month()]+n[1][1].format(" YYYY");if(this.showDropdowns){for(var $=n[1][1].month(),P=n[1][1].year(),w=C&&C.year()||this.maxYear,x=b&&b.year()||this.minYear,M=P==x,S=P==w,B='<select class="monthselect">',I=0;I<12;I++)(!M||b&&I>=b.month())&&(!S||C&&I<=C.month())?B+="<option value='"+I+"'"+(I===$?" selected='selected'":"")+">"+this.locale.monthNames[I]+"</option>":B+="<option value='"+I+"'"+(I===$?" selected='selected'":"")+" disabled='disabled'>"+this.locale.monthNames[I]+"</option>";B+="</select>";for(var L='<select class="yearselect">',A=x;A<=w;A++)L+='<option value="'+A+'"'+(A===P?' selected="selected"':"")+">"+A+"</option>";L+="</select>",_=B+L}if(Y+='<th colspan="5" class="month">'+_+"</th>",(!C||C.isAfter(n.lastDay))&&(!this.linkedCalendars||"right"==a||this.singleDatePicker)?Y+='<th class="next available"><span></span></th>':Y+="<th></th>",Y+="</tr>",Y+="<tr>",(this.showWeekNumbers||this.showISOWeekNumbers)&&(Y+='<th class="week">'+this.locale.weekLabel+"</th>"),e.each(this.locale.daysOfWeek,function(t,e){Y+="<th>"+e+"</th>"}),Y+="</tr>",Y+="</thead>",Y+="<tbody>",null==this.endDate&&this.maxSpan){var E=this.startDate.clone().add(this.maxSpan).endOf("day");(!C||E.isBefore(C))&&(C=E)}for(var s=0;s<6;s++){Y+="<tr>",this.showWeekNumbers?Y+='<td class="week">'+n[s][0].week()+"</td>":this.showISOWeekNumbers&&(Y+='<td class="week">'+n[s][0].isoWeek()+"</td>");for(var i=0;i<7;i++){var O=[];n[s][i].isSame(new Date,"day")&&O.push("today"),n[s][i].isoWeekday()>5&&O.push("weekend"),n[s][i].month()!=n[1][1].month()&&O.push("off","ends"),this.minDate&&n[s][i].isBefore(this.minDate,"day")&&O.push("off","disabled"),C&&n[s][i].isAfter(C,"day")&&O.push("off","disabled"),this.isInvalidDate(n[s][i])&&O.push("off","disabled"),n[s][i].format("YYYY-MM-DD")==this.startDate.format("YYYY-MM-DD")&&O.push("active","start-date"),null!=this.endDate&&n[s][i].format("YYYY-MM-DD")==this.endDate.format("YYYY-MM-DD")&&O.push("active","end-date"),null!=this.endDate&&n[s][i]>this.startDate&&n[s][i]<this.endDate&&O.push("in-range");var W=this.isCustomDate(n[s][i]);!1!==W&&("string"==typeof W?O.push(W):Array.prototype.push.apply(O,W));for(var H="",N=!1,y=0;y<O.length;y++)H+=O[y]+" ","disabled"==O[y]&&(N=!0);N||(H+="available"),Y+='<td class="'+H.replace(/^\s+|\s+$/g,"")+'" data-title="r'+s+"c"+i+'">'+n[s][i].date()+"</td>"}Y+="</tr>"}Y+="</tbody>",Y+="</table>",this.container.find(".drp-calendar."+a+" .calendar-table").html(Y)},renderTimePicker:function(t){if("right"!=t||this.endDate){var e,a,i,s=this.maxDate;if(this.maxSpan&&(!this.maxDate||this.startDate.clone().add(this.maxSpan).isBefore(this.maxDate))&&(s=this.startDate.clone().add(this.maxSpan)),"left"==t)a=this.startDate.clone(),i=this.minDate;else if("right"==t){a=this.endDate.clone(),i=this.startDate;var n=this.container.find(".drp-calendar.right .calendar-time");if(""!=n.html()&&(a.hour(isNaN(a.hour())?n.find(".hourselect option:selected").val():a.hour()),a.minute(isNaN(a.minute())?n.find(".minuteselect option:selected").val():a.minute()),a.second(isNaN(a.second())?n.find(".secondselect option:selected").val():a.second()),!this.timePicker24Hour)){var r=n.find(".ampmselect option:selected").val();"PM"===r&&12>a.hour()&&a.hour(a.hour()+12),"AM"===r&&12===a.hour()&&a.hour(0)}a.isBefore(this.startDate)&&(a=this.startDate.clone()),s&&a.isAfter(s)&&(a=s.clone())}e='<select class="hourselect">';for(var o=this.timePicker24Hour?0:1,h=this.timePicker24Hour?23:12,l=o;l<=h;l++){var c=l;this.timePicker24Hour||(c=a.hour()>=12?12==l?12:l+12:12==l?0:l);var d=a.clone().hour(c),m=!1;i&&d.minute(59).isBefore(i)&&(m=!0),s&&d.minute(0).isAfter(s)&&(m=!0),c!=a.hour()||m?m?e+='<option value="'+l+'" disabled="disabled" class="disabled">'+l+"</option>":e+='<option value="'+l+'">'+l+"</option>":e+='<option value="'+l+'" selected="selected">'+l+"</option>"}e+="</select> ",e+=': <select class="minuteselect">';for(var l=0;l<60;l+=this.timePickerIncrement){var f=l<10?"0"+l:l,d=a.clone().minute(l),m=!1;i&&d.second(59).isBefore(i)&&(m=!0),s&&d.second(0).isAfter(s)&&(m=!0),a.minute()!=l||m?m?e+='<option value="'+l+'" disabled="disabled" class="disabled">'+f+"</option>":e+='<option value="'+l+'">'+f+"</option>":e+='<option value="'+l+'" selected="selected">'+f+"</option>"}if(e+="</select> ",this.timePickerSeconds){e+=': <select class="secondselect">';for(var l=0;l<60;l++){var f=l<10?"0"+l:l,d=a.clone().second(l),m=!1;i&&d.isBefore(i)&&(m=!0),s&&d.isAfter(s)&&(m=!0),a.second()!=l||m?m?e+='<option value="'+l+'" disabled="disabled" class="disabled">'+f+"</option>":e+='<option value="'+l+'">'+f+"</option>":e+='<option value="'+l+'" selected="selected">'+f+"</option>"}e+="</select> "}if(!this.timePicker24Hour){e+='<select class="ampmselect">';var p="",u="";i&&a.clone().hour(12).minute(0).second(0).isBefore(i)&&(p=' disabled="disabled" class="disabled"'),s&&a.clone().hour(0).minute(0).second(0).isAfter(s)&&(u=' disabled="disabled" class="disabled"'),a.hour()>=12?e+='<option value="AM"'+p+'>AM</option><option value="PM" selected="selected"'+u+">PM</option>":e+='<option value="AM" selected="selected"'+p+'>AM</option><option value="PM"'+u+">PM</option>",e+="</select>"}this.container.find(".drp-calendar."+t+" .calendar-time").html(e)}},updateFormInputs:function(){this.singleDatePicker||this.endDate&&(this.startDate.isBefore(this.endDate)||this.startDate.isSame(this.endDate))?this.container.find("button.applyBtn").prop("disabled",!1):this.container.find("button.applyBtn").prop("disabled",!0)},move:function(){var t,a={top:0,left:0},i=this.drops,s=e(window).width();switch(this.parentEl.is("body")||(a={top:this.parentEl.offset().top-this.parentEl.scrollTop(),left:this.parentEl.offset().left-this.parentEl.scrollLeft()},s=this.parentEl[0].clientWidth+this.parentEl.offset().left),i){case"auto":(t=this.element.offset().top+this.element.outerHeight()-a.top)+this.container.outerHeight()>=this.parentEl[0].scrollHeight&&(t=this.element.offset().top-this.container.outerHeight()-a.top,i="up");break;case"up":t=this.element.offset().top-this.container.outerHeight()-a.top;break;default:t=this.element.offset().top+this.element.outerHeight()-a.top}this.container.css({top:0,left:0,right:"auto"});var n=this.container.outerWidth();if(this.container.toggleClass("drop-up","up"==i),"left"==this.opens){var r=s-this.element.offset().left-this.element.outerWidth();n+r>e(window).width()?this.container.css({top:t,right:"auto",left:9}):this.container.css({top:t,right:r,left:"auto"})}else if("center"==this.opens){var o=this.element.offset().left-a.left+this.element.outerWidth()/2-n/2;o<0?this.container.css({top:t,right:"auto",left:9}):o+n>e(window).width()?this.container.css({top:t,left:"auto",right:0}):this.container.css({top:t,left:o,right:"auto"})}else{var o=this.element.offset().left-a.left;o+n>e(window).width()?this.container.css({top:t,left:"auto",right:0}):this.container.css({top:t,left:o,right:"auto"})}},show:function(t){this.isShowing||(this._outsideClickProxy=e.proxy(function(t){this.outsideClick(t)},this),e(document).on("mousedown.daterangepicker",this._outsideClickProxy).on("touchend.daterangepicker",this._outsideClickProxy).on("click.daterangepicker","[data-toggle=dropdown]",this._outsideClickProxy).on("focusin.daterangepicker",this._outsideClickProxy),e(window).on("resize.daterangepicker",e.proxy(function(t){this.move(t)},this)),this.oldStartDate=this.startDate.clone(),this.oldEndDate=this.endDate.clone(),this.previousRightTime=this.endDate.clone(),this.updateView(),this.container.show(),this.move(),this.element.trigger("show.daterangepicker",this),this.isShowing=!0)},hide:function(t){this.isShowing&&(this.endDate||(this.startDate=this.oldStartDate.clone(),this.endDate=this.oldEndDate.clone()),this.startDate.isSame(this.oldStartDate)&&this.endDate.isSame(this.oldEndDate)||this.callback(this.startDate.clone(),this.endDate.clone(),this.chosenLabel),this.updateElement(),e(document).off(".daterangepicker"),e(window).off(".daterangepicker"),this.container.hide(),this.element.trigger("hide.daterangepicker",this),this.isShowing=!1)},toggle:function(t){this.isShowing?this.hide():this.show()},outsideClick:function(t){var a=e(t.target);"focusin"==t.type||a.closest(this.element).length||a.closest(this.container).length||a.closest(".calendar-table").length||(this.hide(),this.element.trigger("outsideClick.daterangepicker",this))},showCalendars:function(){this.container.addClass("show-calendar"),this.move(),this.element.trigger("showCalendar.daterangepicker",this)},hideCalendars:function(){this.container.removeClass("show-calendar"),this.element.trigger("hideCalendar.daterangepicker",this)},clickRange:function(t){var e=t.target.getAttribute("data-range-key");if(this.chosenLabel=e,e==this.locale.customRangeLabel)this.showCalendars();else{var a=this.ranges[e];this.startDate=a[0],this.endDate=a[1],this.timePicker||(this.startDate.startOf("day"),this.endDate.endOf("day")),this.alwaysShowCalendars||this.hideCalendars(),this.clickApply()}},clickPrev:function(t){e(t.target).parents(".drp-calendar").hasClass("left")?(this.leftCalendar.month.subtract(1,"month"),this.linkedCalendars&&this.rightCalendar.month.subtract(1,"month")):this.rightCalendar.month.subtract(1,"month"),this.updateCalendars()},clickNext:function(t){e(t.target).parents(".drp-calendar").hasClass("left")?this.leftCalendar.month.add(1,"month"):(this.rightCalendar.month.add(1,"month"),this.linkedCalendars&&this.leftCalendar.month.add(1,"month")),this.updateCalendars()},hoverDate:function(t){if(e(t.target).hasClass("available")){var a=e(t.target).attr("data-title"),i=a.substr(1,1),s=a.substr(3,1),n=e(t.target).parents(".drp-calendar").hasClass("left")?this.leftCalendar.calendar[i][s]:this.rightCalendar.calendar[i][s],r=this.leftCalendar,o=this.rightCalendar,h=this.startDate;this.endDate||this.container.find(".drp-calendar tbody td").each(function(t,a){if(!e(a).hasClass("week")){var i=e(a).attr("data-title"),s=i.substr(1,1),l=i.substr(3,1),c=e(a).parents(".drp-calendar").hasClass("left")?r.calendar[s][l]:o.calendar[s][l];c.isAfter(h)&&c.isBefore(n)||c.isSame(n,"day")?e(a).addClass("in-range"):e(a).removeClass("in-range")}})}},clickDate:function(t){if(e(t.target).hasClass("available")){var a=e(t.target).attr("data-title"),i=a.substr(1,1),s=a.substr(3,1),n=e(t.target).parents(".drp-calendar").hasClass("left")?this.leftCalendar.calendar[i][s]:this.rightCalendar.calendar[i][s];if(this.endDate||n.isBefore(this.startDate,"day")){if(this.timePicker){var r=parseInt(this.container.find(".left .hourselect").val(),10);if(!this.timePicker24Hour){var o=this.container.find(".left .ampmselect").val();"PM"===o&&r<12&&(r+=12),"AM"===o&&12===r&&(r=0)}var h=parseInt(this.container.find(".left .minuteselect").val(),10);isNaN(h)&&(h=parseInt(this.container.find(".left .minuteselect option:last").val(),10));var l=this.timePickerSeconds?parseInt(this.container.find(".left .secondselect").val(),10):0;n=n.clone().hour(r).minute(h).second(l)}this.endDate=null,this.setStartDate(n.clone())}else if(!this.endDate&&n.isBefore(this.startDate))this.setEndDate(this.startDate.clone());else{if(this.timePicker){var r=parseInt(this.container.find(".right .hourselect").val(),10);if(!this.timePicker24Hour){var o=this.container.find(".right .ampmselect").val();"PM"===o&&r<12&&(r+=12),"AM"===o&&12===r&&(r=0)}var h=parseInt(this.container.find(".right .minuteselect").val(),10);isNaN(h)&&(h=parseInt(this.container.find(".right .minuteselect option:last").val(),10));var l=this.timePickerSeconds?parseInt(this.container.find(".right .secondselect").val(),10):0;n=n.clone().hour(r).minute(h).second(l)}this.setEndDate(n.clone()),this.autoApply&&(this.calculateChosenLabel(),this.clickApply())}this.singleDatePicker&&(this.setEndDate(this.startDate),!this.timePicker&&this.autoApply&&this.clickApply()),this.updateView(),t.stopPropagation()}},calculateChosenLabel:function(){var t=!0,e=0;for(var a in this.ranges){if(this.timePicker){var i=this.timePickerSeconds?"YYYY-MM-DD HH:mm:ss":"YYYY-MM-DD HH:mm";if(this.startDate.format(i)==this.ranges[a][0].format(i)&&this.endDate.format(i)==this.ranges[a][1].format(i)){t=!1,this.chosenLabel=this.container.find(".ranges li:eq("+e+")").addClass("active").attr("data-range-key");break}}else if(this.startDate.format("YYYY-MM-DD")==this.ranges[a][0].format("YYYY-MM-DD")&&this.endDate.format("YYYY-MM-DD")==this.ranges[a][1].format("YYYY-MM-DD")){t=!1,this.chosenLabel=this.container.find(".ranges li:eq("+e+")").addClass("active").attr("data-range-key");break}e++}t&&(this.showCustomRangeLabel?this.chosenLabel=this.container.find(".ranges li:last").addClass("active").attr("data-range-key"):this.chosenLabel=null,this.showCalendars())},clickApply:function(t){this.hide(),this.element.trigger("apply.daterangepicker",this)},clickCancel:function(t){this.startDate=this.oldStartDate,this.endDate=this.oldEndDate,this.hide(),this.element.trigger("cancel.daterangepicker",this)},monthOrYearChanged:function(t){var a=e(t.target).closest(".drp-calendar").hasClass("left"),i=this.container.find(".drp-calendar."+(a?"left":"right")),s=parseInt(i.find(".monthselect").val(),10),n=i.find(".yearselect").val();!a&&(n<this.startDate.year()||n==this.startDate.year()&&s<this.startDate.month())&&(s=this.startDate.month(),n=this.startDate.year()),this.minDate&&(n<this.minDate.year()||n==this.minDate.year()&&s<this.minDate.month())&&(s=this.minDate.month(),n=this.minDate.year()),this.maxDate&&(n>this.maxDate.year()||n==this.maxDate.year()&&s>this.maxDate.month())&&(s=this.maxDate.month(),n=this.maxDate.year()),a?(this.leftCalendar.month.month(s).year(n),this.linkedCalendars&&(this.rightCalendar.month=this.leftCalendar.month.clone().add(1,"month"))):(this.rightCalendar.month.month(s).year(n),this.linkedCalendars&&(this.leftCalendar.month=this.rightCalendar.month.clone().subtract(1,"month"))),this.updateCalendars()},timeChanged:function(t){var a=e(t.target).closest(".drp-calendar"),i=a.hasClass("left"),s=parseInt(a.find(".hourselect").val(),10),n=parseInt(a.find(".minuteselect").val(),10);isNaN(n)&&(n=parseInt(a.find(".minuteselect option:last").val(),10));var r=this.timePickerSeconds?parseInt(a.find(".secondselect").val(),10):0;if(!this.timePicker24Hour){var o=a.find(".ampmselect").val();"PM"===o&&s<12&&(s+=12),"AM"===o&&12===s&&(s=0)}if(i){var h=this.startDate.clone();h.hour(s),h.minute(n),h.second(r),this.setStartDate(h),this.singleDatePicker?this.endDate=this.startDate.clone():this.endDate&&this.endDate.format("YYYY-MM-DD")==h.format("YYYY-MM-DD")&&this.endDate.isBefore(h)&&this.setEndDate(h.clone())}else if(this.endDate){var l=this.endDate.clone();l.hour(s),l.minute(n),l.second(r),this.setEndDate(l)}this.updateCalendars(),this.updateFormInputs(),this.renderTimePicker("left"),this.renderTimePicker("right")},elementChanged:function(){if(this.element.is("input")&&this.element.val().length){var e=this.element.val().split(this.locale.separator),a=null,i=null;2===e.length&&(a=t(e[0],this.locale.format),i=t(e[1],this.locale.format)),(this.singleDatePicker||null===a||null===i)&&(i=a=t(this.element.val(),this.locale.format)),a.isValid()&&i.isValid()&&(this.setStartDate(a),this.setEndDate(i),this.updateView())}},keydown:function(t){(9===t.keyCode||13===t.keyCode)&&this.hide(),27===t.keyCode&&(t.preventDefault(),t.stopPropagation(),this.hide())},updateElement:function(){if(this.element.is("input")&&this.autoUpdateInput){var t=this.startDate.format(this.locale.format);this.singleDatePicker||(t+=this.locale.separator+this.endDate.format(this.locale.format)),t!==this.element.val()&&this.element.val(t).trigger("change")}},remove:function(){this.container.remove(),this.element.off(".daterangepicker"),this.element.removeData()}},e.fn.daterangepicker=function(t,i){var s=e.extend(!0,{},e.fn.daterangepicker.defaultOptions,t);return this.each(function(){var t=e(this);t.data("daterangepicker")&&t.data("daterangepicker").remove(),t.data("daterangepicker",new a(t,s,i))}),this},a});