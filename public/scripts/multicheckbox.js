(function( $ ) {
	$.fn.multipleCheckbox = function(options) {
		var defaults = {
			elemItem	: ".multicheckbox-item",
			elemForm	: ".multicheckbox-form",
			elemSelect	: ".multicheckbox-select",
			elemClear	: ".multicheckbox-clear",
			elemCount	: ".multicheckbox-count",
			addItem		: function(elem){
				var val = $(elem).val();
				var text = $(elem).parents('tr').children().eq(2).html();
				$(this.elemSelect).prepend('<option selected="selected" value="'+ val +'">'+text+'</option>');
				$(this.elemSelect).next().prepend('<option value="'+ val +'">- '+text+'</option>');
				this.countItem();
			},
			clearItem	: function(elem){
				var val = $(elem).val();
				$(this.elemSelect).find('option[value="'+ val +'"]').remove();
				$(this.elemSelect).next().find('option[value="'+ val +'"]').remove();
				this.countItem();
			},
			addAll		: function() {
				var elemSelect = $(this.elemSelect);
				var elemItem = $(this.elemItem);
				elemSelect.html('');
				elemSelect.next().html('')
				elemItem.each(function(){
					var val = $(this).val();
					var text = $(this).closest('tr').find('td:nth-child(3)').html();
					elemSelect.prepend('<option selected="selected" value="'+ val +'">'+text+'</option>');
					elemSelect.next().prepend('<option value="'+ val +'">- '+text+'</option>');
				});
				this.countItem();
			},
			clearAll	: function() {
				$(this.elemSelect).html('');
				$(this.elemSelect).next().html('');
				this.countItem();
			},
			countItem	: function(){
				var count = $(this.elemSelect).children().length;
				if( count > 0 ){
					$(this.elemForm).slideDown();
				} else {
					$(this.elemForm).slideUp();
				}
				$( this.elemCount ).html( count );
			},
			init		: function(elem){
				this.clearAll();
				$(this.elemSelect).hide();
				var string = this.elemSelect.substr(1);
				var clasOrid = this.elemSelect.substr(0,1);
				var cloning = $(this.elemSelect).clone();
				if( $('.'+ string + '_clone').length < 1 ) {
					if(clasOrid == "#"){
						cloning.insertAfter($(this.elemSelect)).removeAttr('name').attr('id','').addClass(string + '_clone').show();
					} else {
						cloning.insertAfter($(this.elemSelect)).removeAttr('name').removeClass(string).addClass(string + '_clone').show();
					}
				}

				elem.prop("checked", false);
			},
			ajaxCallback : function(response){
				if ( response.status == "success" ){
					toastr.success(response.message,'Success !',{ 
						progressBar: true, 
						timeOut: 1000
					});
					setTimeout(function(){
						if(response.redirect != ""){
							location.href = response.redirect;
						} else {
							location.reload();
						}
					},1000);
				} else {
					toastr.error(response.message,'Failed !');
				}
			}
		};
		var settings = $.extend( {}, defaults, options );
		return this.each(function() {
			settings.init($(this));
			var elemCheckALL = this;
			$(this).change(function(){
				if(this.checked){
					$(settings.elemItem).prop("checked", true);
					settings.addAll();
				} else {
					$(settings.elemItem).prop("checked", false);
					settings.clearAll();
				}
			});
			$(settings.elemItem).change(function(){
				var countAllItem = $(settings.elemItem ).length;
				var countChecked = $(settings.elemItem + ':checked').length;
				if( countChecked == countAllItem ){
					$(elemCheckALL).prop("checked", true);
				} else {
					$(elemCheckALL).prop("checked", false);
				}
				if(this.checked){
					settings.addItem(this);
				} else {
					settings.clearItem(this);
				}
			});
			$(settings.elemClear).click(function(e){
				e.preventDefault();
				$(elemCheckALL).prop("checked", false);
				$(settings.elemItem).prop("checked", false);
				settings.clearAll();
			});
 			$(settings.elemForm).submit(function(e){
				e.preventDefault();
				var form = $(this);
				var btnHtml = form.find("[type='submit']").html();
				var url = form.attr("action");
				var data = new FormData(this);
				$.ajax({
					cache: false,
					processData: false,
					contentType: false,
					type: "POST",
					url : url,
					data : data,
					dataType:'JSON',
					success:function(response) {
						form.find("[type='submit']").removeClass("disabled").html(btnHtml);
						$(elemCheckALL).prop("checked", false);
						$(settings.elemItem).prop("checked", false);
						settings.ajaxCallback(response);
					}
				});
			});
		}); 
	};
})( jQuery );

(function( $ ) {
	$.fn.multipleCheckboxGaleri = function(options) {
		var defaults = {
			elemItem	: ".multicheckbox-item",
			elemForm	: ".multicheckbox-form",
			elemSelect	: ".multicheckbox-select",
			elemClear	: ".multicheckbox-clear",
			elemCount	: ".multicheckbox-count",
			addItem		: function(elem){
				var val = $(elem).val();
				var text = $(elem).parent().text();
				$(this.elemSelect).prepend('<option selected="selected" value="'+ val +'">'+text+'</option>');
				$(this.elemSelect).next().prepend('<option value="'+ val +'">- '+text+'</option>');
				this.countItem();
			},
			clearItem	: function(elem){
				var val = $(elem).val();
				$(this.elemSelect).find('option[value="'+ val +'"]').remove();
				$(this.elemSelect).next().find('option[value="'+ val +'"]').remove();
				this.countItem();
			},
			addAll		: function() {
				var elemSelect = $(this.elemSelect);
				var elemItem = $(this.elemItem);
				elemSelect.html('');
				elemSelect.next().html('')
				elemItem.each(function(){
					var val = $(this).val();
					var text = $(this).parent().text();
					elemSelect.prepend('<option selected="selected" value="'+ val +'">'+text+'</option>');
					elemSelect.next().prepend('<option value="'+ val +'">- '+text+'</option>');
				});
				this.countItem();
			},
			clearAll	: function() {
				$(this.elemSelect).html('');
				$(this.elemSelect).next().html('');
				this.countItem();
			},
			countItem	: function(){
				var count = $(this.elemSelect).children().length;
				if( count > 0 ){
					$(this.elemForm).slideDown();
				} else {
					$(this.elemForm).slideUp();
				}
				$( this.elemCount ).html( count );
			},
			init		: function(elem){
				this.clearAll();
				$(this.elemSelect).hide();
				var string = this.elemSelect.substr(1);
				var clasOrid = this.elemSelect.substr(0,1);
				var cloning = $(this.elemSelect).clone();
				if( $('.'+ string + '_clone').length < 1 ) {
					if(clasOrid == "#"){
						cloning.insertAfter($(this.elemSelect)).removeAttr('name').attr('id','').addClass(string + '_clone').show();
					} else {
						cloning.insertAfter($(this.elemSelect)).removeAttr('name').removeClass(string).addClass(string + '_clone').show();
					}
				}

				elem.prop("checked", false);
			},
			ajaxCallback : function(response){
				if ( response.status == "success" ){
					toastr.success(response.message,'Success !',{ 
						progressBar: true, 
						timeOut: 1000
					});
					setTimeout(function(){
						if(response.redirect != ""){
							location.href = response.redirect;
						} else {
							location.reload();
						}
					},1000);
				} else {
					toastr.error(response.message,'Failed !');
				}
			}
		};
		var settings = $.extend( {}, defaults, options );
		return this.each(function() {
			settings.init($(this));
			var elemCheckALL = this;
			$(this).change(function(){
				if(this.checked){
					$(settings.elemItem).prop("checked", true);
					settings.addAll();
				} else {
					$(settings.elemItem).prop("checked", false);
					settings.clearAll();
				}
			});
			$(settings.elemItem).change(function(){
				var countAllItem = $(settings.elemItem ).length;
				var countChecked = $(settings.elemItem + ':checked').length;
				if( countChecked == countAllItem ){
					$(elemCheckALL).prop("checked", true);
				} else {
					$(elemCheckALL).prop("checked", false);
				}
				if(this.checked){
					settings.addItem(this);
				} else {
					settings.clearItem(this);
				}
			});
			$(settings.elemClear).click(function(e){
				e.preventDefault();
				$(elemCheckALL).prop("checked", false);
				$(settings.elemItem).prop("checked", false);
				settings.clearAll();
			});
 			$(settings.elemForm).submit(function(e){
				e.preventDefault();
				var form = $(this);
				var btnHtml = form.find("[type='submit']").html();
				var url = form.attr("action");
				var data = new FormData(this);
				$.ajax({
					beforeSend:function() { 
						form.find("[type='submit']").addClass("disabled").html("<i class='fa fa-spinner fa-pulse fa-fw'></i> Loading ... ");
					},
					cache: false,
					processData: false,
					contentType: false,
					type: "POST",
					url : url,
					data : data,
					dataType:'JSON',
					success:function(response) {
						form.find("[type='submit']").removeClass("disabled").html(btnHtml);
						$(elemCheckALL).prop("checked", false);
						$(settings.elemItem).prop("checked", false);
						settings.ajaxCallback(response);
					}
				});
			});
		}); 
	};
})( jQuery );