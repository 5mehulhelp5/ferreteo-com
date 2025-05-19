/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define(
    [
        'jquery',
        'jquery/ui',
        "Aitoc_ProductUnitsAndQuantities/js/quantities/qty-control-applier",
        'Aitoc_ProductUnitsAndQuantities/js/quantities/current-product-helper'
    ],
    function ($, jQueryUi, qtyControlApplier, currentProductHelper) {
        'use strict';

        return function (original) {
            if (!original) {
                return original;
            }

            $.widget('mage.SwatchRendererTooltip', $.mage.SwatchRendererTooltip, {
                _init: function () {
                    console.log('------------------- tooltip -----------------------');
                    var $widget = this,
                        $this = this.element,
                        $element = $('.' + $widget.options.tooltipClass),
                        timer,
                        type = parseInt($this.data('option-type'), 10),
                        label = $this.data('option-label'),
                        thumb = $this.data('option-tooltip-thumb'),
                        value = $this.data('option-tooltip-value'),
                        width = $this.data('thumb-width'),
                        height = $this.data('thumb-height'),
                        $image,
                        $title,
                        $corner;

                    if (!$element.length) {
                        $element = $('<div class="' +
                            $widget.options.tooltipClass +
                            '"><div class="image"></div><div class="title"></div><div class="corner"></div></div>'
                        );
                        $('body').append($element);
                    }

                    $image = $element.find('.image');
                    $title = $element.find('.title');
                    $corner = $element.find('.corner');

                    $this.hover(function () {
                        if (!$this.hasClass('disabled')) {
                            timer = setTimeout(
                                function () {
                                    var leftOpt = null,
                                        leftCorner = 0,
                                        left,
                                        $window;

                                    if (type === 2) {
                                        // Image
                                        $image.css({
                                            'background': 'url("' + thumb + '") no-repeat center', //Background case
                                            'background-size': 'initial',
                                            'width': width + 'px',
                                            'height': height + 'px'
                                        });
                                        $image.show();
                                    } else if (type === 1) {
                                        // Color
                                        $image.css({
                                            background: value
                                        });
                                        $image.show();
                                    } else if (type === 0 || type === 3) {
                                        // Default
                                        $image.hide();
                                    }

                                    $title.text(label);

                                    leftOpt = $this.offset().left;
                                    left = leftOpt + $this.width() / 2 - $element.width() / 2;
                                    $window = $(window);

                                    // the numbers (5 and 5) is magick constants for offset from left or right page
                                    if (left < 0) {
                                        left = 5;
                                    } else if (left + $element.width() > $window.width()) {
                                        left = $window.width() - $element.width() - 5;
                                    }

                                    // the numbers (6,  3 and 18) is magick constants for offset tooltip
                                    leftCorner = 0;

                                    if ($element.width() < $this.width()) {
                                        leftCorner = $element.width() / 2 - 3;
                                    } else {
                                        leftCorner = (leftOpt > left ? leftOpt - left : left - leftOpt) + $this.width() / 2 - 6;
                                    }

                                    $corner.css({
                                        left: leftCorner
                                    });
                                    $element.css({
                                        left: left,
                                        top: $this.offset().top - $element.height() - $corner.height() - 18 + 78
                                    }).show();
                                },
                                $widget.options.delay
                            );
                        }
                    }, function () {
                        $element.hide();
                        clearTimeout(timer);
                    });

                    $(document).on('tap', function () {
                        $element.hide();
                        clearTimeout(timer);
                    });

                    $this.on('tap', function (event) {
                        event.stopPropagation();
                    });
                }
            });

            $.widget('mage.SwatchRenderer', $.mage.SwatchRenderer, {
              _RenderSwatchOptions: function (config, controlId) {
                  var optionConfig = this.options.jsonSwatchConfig[config.id],
                      optionClass = this.options.classes.optionClass,
                      sizeConfig = this.options.jsonSwatchImageSizeConfig,
                      moreLimit = parseInt(this.options.numberToShow, 10),
                      moreClass = this.options.classes.moreButton,
                      moreText = this.options.moreButtonText,
                      countAttributes = 0,
                      html = '';

                  if (!this.options.jsonSwatchConfig.hasOwnProperty(config.id)) {
                      return '';
                  }
                  console.log('---------- config.options ------------');
                  console.log(config.options);
                  $.each(config.options, function (index) {
                      var id,
                          type,
                          value,
                          thumb,
                          label,
                          width,
                          height,
                          attr,
                          swatchImageWidth,
                          swatchImageHeight;

                      if (!optionConfig.hasOwnProperty(this.id)) {
                          return '';
                      }

                      // Add more button
                      countAttributes = countAttributes+1;
                      console.log('------------countAttributes--------------');
                      console.log(countAttributes);
                      console.log('------------moreLimit--------------');
                      console.log(moreLimit);



                      id = this.id;
                      type = parseInt(optionConfig[id].type, 10);
                      value = optionConfig[id].hasOwnProperty('value') ?
                          $('<i></i>').text(optionConfig[id].value).html() : '';
                      thumb = optionConfig[id].hasOwnProperty('thumb') ? optionConfig[id].thumb : '';
                      width = _.has(sizeConfig, 'swatchThumb') ? sizeConfig.swatchThumb.width : 110;
                      height = _.has(sizeConfig, 'swatchThumb') ? sizeConfig.swatchThumb.height : 90;
                      label = this.label ? $('<i></i>').text(this.label).html() : '';
                      attr =
                          ' id="' + controlId + '-item-' + id + '"' +
                          ' index="' + index + '"' +
                          ' aria-checked="false"' +
                          ' aria-describedby="' + controlId + '"' +
                          ' tabindex="0"' +
                          ' data-option-type="' + type + '"' +
                          ' data-option-id="' + id + '"' +
                          ' data-option-label="' + label + '"' +
                          ' aria-label="' + label + '"' +
                          ' role="option"' +
                          ' data-thumb-width="' + width + '"' +
                          ' data-thumb-height="' + height + '"';

                      attr += thumb !== '' ? ' data-option-tooltip-thumb="' + thumb + '"' : '';
                      attr += value !== '' ? ' data-option-tooltip-value="' + value + '"' : '';

                      swatchImageWidth = _.has(sizeConfig, 'swatchImage') ? sizeConfig.swatchImage.width : 30;
                      swatchImageHeight = _.has(sizeConfig, 'swatchImage') ? sizeConfig.swatchImage.height : 20;

                      if (!this.hasOwnProperty('products') || this.products.length <= 0) {
                          attr += ' data-option-empty="true"';
                      }

                      if (type === 0) {
                          // Text
                          html += '<div class="' + optionClass + ' text" ' + attr + '>' + (value ? value : label) +
                              '</div>';
                      } else if (type === 1) {
                          // Color
                          html += '<div class="' + optionClass + ' color" ' + attr +
                              ' style="background: ' + value +
                              ' no-repeat center; background-size: initial;">' + '' +
                              '</div>';
                      } else if (type === 2) {
                          // Image
                          html += '<div class="' + optionClass + ' image" ' + attr +
                              ' style="background: url(' + value + ') no-repeat center; background-size: initial;width:' +
                              swatchImageWidth + 'px; height:' + swatchImageHeight + 'px">' + '' +
                              '</div>';
                      } else if (type === 3) {
                          // Clear
                          html += '<div class="' + optionClass + '" ' + attr + '></div>';
                      } else {
                          // Default
                          html += '<div class="' + optionClass + '" ' + attr + '>' + label + '</div>';
                      }
                      if (moreLimit === countAttributes) {
                          html += '<a href="#" class="' + moreClass + '"><span>' + moreText + '</span></a>';
                          html += '<div class="swat-pop-container"><div class="swat-pop">';
                      }
                      if (countAttributes > moreLimit && config.options.length === countAttributes) {
                          html += '</div></div>';
                      }
                  });

                  return html;
              },
              _OnMoreClick: function ($this) {
                  $this.nextAll().show();
                  $this.next('.swat-pop-container').toggleClass('active');
              }
            });

            return $.mage.SwatchRenderer;
        }
    }
);
