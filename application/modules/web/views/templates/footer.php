<!-- footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="menu col-xs-12 col-sm-3">
                <div>
                    <nav role="navigation">
                        <ul data-region="footer_menu_links">
                            <li><a href="javascript:void(0)">About us</a></li>
                            <li><a href="javascript:void(0)">Contact</a></li>
                            <li><a href="javascript:void(0)">Terms of sale and delivery - SG Production/Riegens UK</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="postal col-xs-12 col-sm-3">
                <div class="block-content block-content--">
                    <h4>Address</h4>
                    <ul>
                        <li><figure class="fa fa fa-user"></figure><span class="addre-name">SG Armaturen AS</span></li>
                        <li><figure class="fa fa fa-map-marker"></figure><span class="address-address">Skytterheia 25, N-4790<br />
                                Lillesand<br />
                                Norway</span></li>
                    </ul>
                </div>
            </div>
            <div class="address col-xs-12 col-sm-3">
                <div class="block-content block-content--">
                    <h4>Contact info</h4>
                    <ul>
                        <li><figure class="fa fa-envelope"></figure><span class="contact-email"><a href="mailto:firmapost@sg-as.no">firmapost@sg-as.no</a></span></li>
                        <li><figure class="fa fa-phone"></figure><span class="contact-phone">+47 37 500 300</span></li>
                        <li><figure class="fa fa-fax"></figure><span class="contact-fax">+47 37 500 301</span></li>
                    </ul>
                </div>
            </div>
            <div class="social-icons col-xs-12 col-sm-3">
                <div class="social-icon">
                    <a href="https://www.linkedin.com/company/77378/" class="linkedin" target="_blank">
                        <img src="public/images/linkedIn.svg" alt="linkedin"/>
                    </a>
                </div>
                <div class="social-icon">
                    <a href="https://www.youtube.com/channel/UC7j_QJGBOvEC2gB6edFpV_A" class="youtube" target="_blank">
                        <img src="public/images/youtube.svg" alt="youtube"/>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-branding">
        <div class="container">
            <div class="block-content block-content--">
                <p>SG Armaturen Copyright All Rights Reserved © 2017</p>
            </div>
        </div>
    </div>
</footer>
<!-- //footer -->

</div>

<script src="public/js/web/common.js"></script>
<script>
    $(document).ready(function () {

        /* on type close icon show in search field */
        $("#search-box").keyup(function () {
            if ($(this).val()) {
                $('.close-ico').show();
            } else {
                $('.close-ico').hide();
            }
        });

        $(".close-ico").on("click", function () {
            $("#search-box").val('');
            $('.close-ico').hide();
        })
        /* on type close icon show in search field end */

    })
</script>

</body>
</html>