(function ($, window) {
    window['StackedMenu'] = require('./stacked-menu');
    window['PerfectScrollbar'] = require('perfect-scrollbar').default;

    const Popper = require('popper.js').default;

    const Starter = {

        init() {
            const self = this;

            // settings
            // =============================================================
            // Turn off the transform placement on Popper
            Popper.Defaults.modifiers.computeStyle.gpuAcceleration = false;

            // event handlers
            // =============================================================

            $('body').on('click', '.stop-propagation', function (e) {
                e.stopPropagation()
            })
                .on('click', '.prevent-default', function (e) {
                    e.preventDefault()
                });


            // polyfill
            // =============================================================

            this.handlePlaceholderShown();


            // bootstrap components
            // =============================================================

            this.initTooltips();
            this.initPopovers();
            this.handleInputGroup();
            this.handleCustomFileInput();
            this.handlePasswordVisibility();
            this.handleIndeterminateCheckboxes();
            this.handleFormValidation();
            this.handleCardExpansion();
            this.handleModalOverflow();


            // theme components
            // =============================================================

            this.initBackdrop();
            this.topBarSearch();
            this.toggleHamburgerMenu();
            this.handleAside();
            this.handleScrollable();
            this.handleStackedMenu();
            this.handleSidebar();
            this.handlePublisher();
            this.handleMasonryLayout();
            this.handleSmoothScroll();


            // handle window load & resize
            // =============================================================

            $(window).on('load', function () {
                const $target = $('.stacked-menu .menu > li.has-active');
                if ($target.length) {
                    $('#aside-menu').animate({
                        scrollTop: $target.position().top
                    })
                }
            })
                .on('resize', function () {
                    // force close aside on toggle screen up
                    if (self.isToggleScreenUp() && $('.app-aside').hasClass('has-open') && !$('.app').hasClass('has-fullwidth')) {
                        self.closeAside()
                    }

                    // disable transition temporary
                    $('.app-aside, .page-sidebar').addClass('notransition');
                    setTimeout(function () {
                        $('.app-aside, .page-sidebar').removeClass('notransition')
                    }, 1)
                })
        },


        // Polyfill for :placeholder-shown
        // =============================================================

        handlePlaceholderShown() {
            $(document).on('load keyup change', '[placeholder]', function () {
                this.classList[this.value ? 'remove' : 'add']('placeholder-shown')
            })
        },


        // Handle Bootstrap components
        // =============================================================

        initTooltips() {
            $('[data-toggle="tooltip"]').tooltip()
        },

        initPopovers() {
            $('[data-toggle="popover"]').popover()
        },

        handleInputClearable(target) {
            const isEmpty = !$(target).val();
            const clearable = $(target).parent().children('.close');

            clearable.toggleClass('show', !isEmpty)
        },

        handleInputGroup() {
            const self = this;

            // initialize events
            $('.has-clearable > .form-control').each(function () {
                self.handleInputClearable(this)
            });

            // handle input group event
            $(document).on('focus', '.input-group:not(.input-group-alt) .form-control', function () {
                const hasInputGroup = $(this).parent().has('.input-group');
                if (hasInputGroup) {
                    $(this).parent().addClass('focus')
                }
            })
                .on('blur', '.input-group:not(.input-group-alt) .form-control', function () {
                    const hasInputGroup = $(this).parent().has('.input-group');
                    if (hasInputGroup) {
                        $(this).parent().removeClass('focus')
                    }
                })
                // input has clearable
                .on('keyup', '.has-clearable > .form-control', function () {
                    self.handleInputClearable(this)
                })
                .on('click', '.has-clearable > .close', function () {
                    const input = $(this).parent().children('.form-control');

                    input.val('').focus();
                    self.handleInputClearable(input)
                })

        },

        handleCustomFileInput() {
            // custom file input behavior
            $('.custom-file > .custom-file-label').each(function () {
                const label = $(this).text();
                $(this).data('label', label)
            });

            $(document).on('change', '.custom-file > .custom-file-input', function () {
                const files = this.files;
                const $fileLabel = $(this).next('.custom-file-label');
                const $originLabel = $fileLabel.data('label');

                $fileLabel.text(files.length + ' files selected');
                if (files.length <= 2) {
                    let fileNames = [];
                    for (let i = 0; i < files.length; i++) {
                        fileNames.push(files[i].name)
                    }
                    $fileLabel.text(fileNames.join(', '))
                }
                if (!files.length) {
                    $fileLabel.text($originLabel)
                }
            })
        },

        handlePasswordVisibility() {
            $(document).on('click', '[data-toggle="password"]', function (e) {
                e.preventDefault();
                const target = $(this).attr('href');
                const $target = $(target);

                if ($(this).has('.fa'))
                    $(this).children('.fa, .far').toggleClass('fa-eye fa-eye-slash');

                if ($target.is('[type="password"]')) {
                    $target.prop('type', 'text');
                    $(this).children().last().text('Hide')
                }
                else {
                    $target.prop('type', 'password');
                    $(this).children().last().text('Show')
                }
            })
        },

        handleIndeterminateCheckboxes() {
            $('input[type="checkbox"][indeterminate]').prop('indeterminate', true)
        },

        handleFormValidation() {
            $(window).on('load', function () {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = $('.needs-validation');
                // Loop over them and prevent submission
                forms.each(function (i, form) {
                    $(form).on('submit', function (e) {
                        if (form.checkValidity() === false) {
                            e.preventDefault();
                            e.stopPropagation()
                        }
                        $(form).addClass('was-validated')
                    })
                })
            })
        },

        handleCardExpansion() {
            $(document).on('show.bs.collapse hide.bs.collapse', '.card-expansion-item > .collapse', function (e) {
                const $item = $(this).parent();
                const isShown = e.type === 'show';

                $item.toggleClass('expanded', isShown)
            })
        },

        handleModalOverflow() {
            $('.modal').on('shown.bs.modal', function () {
                $(this)
                    .addClass('has-shown')
                    .find('.modal-body')
                    .trigger('scroll')
            });

            $('.modal-dialog-overflow .modal-body').on('scroll', function () {
                const $elem = $(this);
                const elem = $elem[0];
                const isTop = $elem.scrollTop() === 0;
                const isBottom = elem.scrollHeight - $elem.scrollTop() === $elem.outerHeight();

                $elem.prev().toggleClass('modal-body-scrolled', isTop);
                $elem.next().toggleClass('modal-body-scrolled', isBottom)
            })
        },


        // Handle Theme components
        // =============================================================

        isToggleScreenUp() {
            return window.matchMedia('(min-width: 768px)').matches
        },

        isToggleScreenDown() {
            return window.matchMedia('(max-width: 767.98px)').matches
        },

        initBackdrop() {
            $('.app').append('<div class="app-backdrop"/>')
        },

        openBackdrop() {
            $('.app-backdrop').addClass('show');
            return $('.app-backdrop')
        },

        closeBackdrop() {
            $('.app-backdrop').removeClass('show');
            return $('.app-backdrop')
        },

        topBarSearch() {
            $(document).on('blur', '.top-bar-search > .form-control', function () {
                const $input = $(this).children('.form-control');
                const isEmpty = $input.val().length === 0;

                if (!isEmpty) {
                    $input.val('')
                }
            })
        },

        toggleHamburgerMenu() {
            $(document).on('click', '.js-hamburger', function () {
                $(this).toggleClass('has-active')
            })
        },

        openAside() {
            const self = this;

            const backdrop = this.openBackdrop();

            $('.app-aside').addClass('has-open');
            $('[data-toggle="aside"]').addClass('has-active');

            backdrop.one('click', function () {
                self.closeAside()
            })
        },

        closeAside() {
            this.closeBackdrop();
            $('.app-aside').removeClass('has-open');
            $('[data-toggle="aside"]').removeClass('has-active')
        },

        handleAside() {
            const self = this;
            const $trigger = $('[data-toggle="aside"]');

            $trigger.on('click', function () {
                const isOpen = $('.app-aside').hasClass('has-open');

                $trigger.toggleClass('has-active', !isOpen);

                if (isOpen)
                    self.closeAside();
                else
                    self.openAside()
            })
        },

        handleScrollable() {
            if (window.PerfectScrollbar && $('.has-scrollable').length) {
                $('.has-scrollable').each(function () {
                    return new PerfectScrollbar(this, {
                        suppressScrollX: true
                    })
                })
            }
        },

        handleStackedMenu() {
            if (window.StackedMenu && $('#stacked-menu').length) {
                return new StackedMenu()
            }
        },

        toggleSidebar() {
            const self = this;
            const $page = $('.page.has-sidebar');
            const isOpen = $page.hasClass('has-sidebar-open');

            if ($page.length) {
                $page.toggleClass('has-sidebar-open', !isOpen)
            }
        },

        handleSidebar() {
            const self = this;

            // add sidebar backdrop
            if ($('.has-sidebar').length) {
                $('.page').prepend('<div class="sidebar-backdrop" />')
            }

            $(document).on('click', '[data-toggle="sidebar"], .sidebar-backdrop', function (e) {
                e.preventDefault();
                self.toggleSidebar()
            })
        },

        handlePublisher() {
            $(document)
                .on('focusin', '.publisher .form-control', function () {
                    const $publisher = $(this).parents('.publisher');

                    // normalize all empty publisher
                    $('.publisher').each(function () {
                        const hasEmpty = !$(this).find('.form-control').val();

                        if (hasEmpty) {
                            $(this).removeClass('active');
                            $(this).not('.keep-focus').removeClass('focus')
                        }
                    });

                    // add state classes
                    $publisher.addClass('focus active')
                })
                .on('click', 'html', function () {
                    const $publisher = $('.publisher.active');
                    const isEmpty = !$publisher.find('.form-control').val();

                    // always remove active state
                    $publisher.removeClass('active');

                    // remove focus if input is empty
                    if (isEmpty) {
                        $publisher.not('.keep-focus').removeClass('focus')
                    }
                })
                .on('click', '.publisher.active', function (e) {
                    e.stopPropagation()
                })
        },

        handleMasonryLayout() {
            $(window).on('load', function () {
                if (window.Masonry) {
                    $('.masonry-layout').masonry({
                        itemSelector: '.masonry-item',
                        columnWidth: '.masonry-item:first-child',
                        percentPosition: true
                    })
                }
            })
        },

        handleSmoothScroll() {
            $(document).on('click', 'a.smooth-scroll[href^="#"]', function (e) {
                const hash = $(this).attr('href');
                const target = $(hash);
                if (!target.length) {
                    return
                }

                e.preventDefault();

                const headerHeight = $('.app-header').height() + 20;
                const offset = target.offset().top - headerHeight;

                $('html, body').animate({
                    scrollTop: (offset < 0) ? 0 : offset
                }, 700)
            })
        },


        // Utils

        debounce(func, wait, immediate) {
            var timeout;
            return function () {
                var context = this, args = arguments;
                var later = function () {
                    timeout = null;
                    if (!immediate) func.apply(context, args)
                };
                var callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args)
            }
        }
    };

    Starter.init();
})(jQuery, window);
