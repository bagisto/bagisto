(function anonymous() {
	define(['jquery', 'Magento_Ui/js/modal/modal', '@tensorflow/tfjs', '@tensorflow/netjs'], function ($, modal, tf, mobilenet) {
		'use strict';
		$.widget('image.search', {
			options: {},
			_create: function () {
                var self = this;
                
				$(document).ready(function () {
                    $('div#searched-image img').attr('src', localStorage.searchedImage);
                    
					$('div#searched-image img').error(function () {
						$('.wk-image-search').hide();
                    });
                    
                    let queryText = localStorage.queryText;
                    
					if (queryText != undefined) {
						$('div#related-suggestions').empty();
						$('div#related-suggestions').append('<h5>Filter by analyzed keywords</h5>');
						$.each(queryText.split('+'), function (i, v) {
							$('div#related-suggestions').append('<a href="' +
								self.options.searchUrl + '?q=' + v.trim() + '&imagesearch"><span class="wk-search-terms">' + v.trim() + '</span></a>');
						});
                    }
                    
					if (self.options.deleteNow && localStorage.searchedImage) {
						$.post({
							url: self.options.deleteImageUrl,
							data: {
								url: localStorage.searchedImage
							},
							error: function (resp) {}
						});
                    }
                    
					if (!self.options.hideBreadcrumbs) {
						$('input#search').val('');
						$('div.breadcrumbs').show();
						$('div.page-title-wrapper').show();
                    }
                    
					if (self.options.hideBreadcrumbs) {
						$('input#search').val('');
					}
                });
                


				$('#uploader').change(function () {
                    $("body div#upload-box").hide();
                    
                    $('div.wk-loading-mask').removeClass('wk-display-none');
                    
                    var file_data = $('#uploader').prop('files')[0];
                    
                    var form_data = new FormData();
                    
                    form_data.append('file', file_data);
                    
					$.ajax({
                        url: self.options.processorUrl,
                        
                        type: 'POST',
                        
                        processData: false,
                        
                        contentType: false,
                        
                        data: form_data,
                        
						success: function (data) {
							if (data) {
                                $('#image-to-be-search').attr('src', data);
                                
								let net, analysed_data = '',
									terms_array = [],
                                    imgUrlCurr, queryText;
                                    
								async function app() {
                                    net = await mobilenet.load();
                                    
                                    let imgEl = document.getElementById('image-to-be-search');
                                    
                                    const result = await net.classify(imgEl);
                                    
									$.each(result, function (index, value) {
										analysed_data = analysed_data + ',' + value.className.split(',');
                                    });
                                    
                                    analysed_data = analysed_data.replace(/,/g, "+");
                                    
                                    queryText = analysed_data.substring(1);
                                    
                                    localStorage.queryText = queryText;
                                    
                                    imgUrlCurr = $('#image-to-be-search').attr('src');
                                    
                                    localStorage.imgUrlDel = localStorage.searchedImage;
                                    
                                    localStorage.searchedImage = imgUrlCurr;
                                    
                                    $('.wk-loading-mask').addClass('wk-display-none');
                                    
									window.location.href = self.options.searchUrl + '?q=' + queryText + '&imagesearch';
                                }
                                
								app();
							}
						},
						error: function (resp) {}
					});
                });
                
				$("body").on('click', function (e) {
					if (e.target.className != 'fa fa-camera' && e.target.id != 'browse-label' && e.target.id != 'upload-box') {
						$("body div#upload-box").slideUp();
					}
                });
                
				$("#icon").on('click', function () {
					$("body div#upload-box").slideToggle();
				});
			}
		});
		return $.image.search;
	});
})