<?php

namespace WCGT\WC_Gift_Proceed\Frontend;

class Shortcode {
    
    public function __construct() {
        add_shortcode( 'wcgt_gift', [ $this, 'shortcode_callback_func' ] );
    }

    public function shortcode_callback_func( $atts, $content = '' ) { 
        
    
        $output = '';
        $output .= '<div id="shipping-payment">
            <div class="container">
                <div class="d-flex justify-content-start align-items-start">
                    <div class="content__wrapper">
                        <form method="POST" class="js--confirm-order" action="/confirmOrder">
                            <div class="content__wrapper--shipping">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-start align-items-center">
                                        <h3 class="card-title mb-0">Shipping Address</h3>
                                        <p class="card-subtitle">(Please Fill Out Your Information)</p>
                                    </div>
                                </div>
                                <section class="gift-form-wrapper rounded d-flex justify-content-between mt-2">
                                    <div id="address-from" class="w-50 shipping-form-container">
                                        <h2 class="border-bottom pb-2 gift-from">Gift From</h2>
                                        <fieldset>
                                            <input data-validation="true" data-validation-type="name" type="text" id="sender-name" name="name" required="" value="Rejuan Ahamed">
                                            <label for="name" class="focus">Name</label>
                                            <span class="validation-icon"></span>
                                            <p class="validation-text"></p>
                                            <span class="validation-icon"></span>
                                            <p class="validation-text"></p>
                                        </fieldset>
                                        <fieldset>
                                            <input data-validation-type="phone" type="text" class="js--primary-phone" name="phone" id="sender-phone" required="" value="01727424216">
                                            <label for="phone" class="focus">Phone No</label>
                                            <span class="validation-icon"></span>
                                            <p class="validation-text"></p>
                                            <span class="validation-icon"></span>
                                            <p class="validation-text"></p>
                                        </fieldset>
                                        <fieldset>
                                            <input data-validation-type="phone" type="text" id="sender-phone2" class="js--alternative-phone" name="phone2" value="">
                                            <label for="phone2">Alternative Phone No</label>
                                            <span class="validation-icon"></span>
                                            <p class="validation-text"></p>
                                            <span class="validation-icon"></span>
                                            <p class="validation-text"></p>
                                        </fieldset>
                                        <fieldset>
                                            <select data-validation-type="select-menu" class="custom-select" name="countryId" id="js--from-country" required="">
                                                <option value="1" data-lang-eng="Bangladesh" selected="">Bangladesh</option>
                                                <option value="13" data-lang-eng="United Arab Emirates">United Arab Emirates</option>
                                                <option value="10" data-lang-eng="United Kingdom">United Kingdom</option>
                                                <option value="4" data-lang-eng="USA">USA</option>
                                            </select>
                                            <span class="validation-icon"></span>
                                            <p class="validation-text"></p>
                                            <span class="validation-icon"></span>
                                            <p class="validation-text"></p>
                                        </fieldset>
                                        <div class="d-flex justify-content-between">
                                            <fieldset class="group">
                                                <select data-validation-type="select-menu" class="custom-select" id="js--from-city" name="cityId" required="" style="display: block">
                                                    <option selected="" disabled="" value="">Select City</option>
                                                    <option value="2" data-lang-eng="Dhaka" selected="">ঢাকা</option>
                                                    <option value="4" data-lang-eng="Rajshahi">রাজশাহী</option>
                                                </select>
                                                <span class="validation-icon"></span>
                                                <p class="validation-text"></p>
                                                <span class="validation-icon"></span>
                                                <p class="validation-text"></p>
                                            </fieldset>
                                            <fieldset class="group">
                                                <select data-validation-type="select-menu" class="custom-select" id="js--from-area" name="areaId" required="" style="display: block">
                                                    <option selected="" disabled="" value="">Select Area</option>
                                                    <option value="4" data-lang-eng="Agargaon">আগারগাঁও</option>
                                                    <option value="10" data-lang-eng="Azimpur ">আজিমপুর </option>
                                                </select>
                                                <span class="validation-icon"></span>
                                                <p class="validation-text"></p>
                                                <span class="validation-icon"></span>
                                                <p class="validation-text"></p>
                                            </fieldset>
                                        </div>
                                        <fieldset>
                                            <textarea data-validation-type="address" name="address" id="sender-address" required="">House #592, STR Shenpara, Mirpur 10</textarea>
                                            <label for="address" class="focus">Address</label>
                                            <span class="validation-icon"></span>
                                            <p class="validation-text"></p>
                                            <span class="validation-icon"></span>
                                            <p class="validation-text"></p>
                                        </fieldset>
                                    </div>
                                    <div id="address-to" class="w-50 shipping-form-container">
                                        <h2 class="border-bottom pb-2 gift-to">Gift To</h2>
                                        <fieldset>
                                            <input data-validation-type="name" type="text" name="giftName" id="giftName" required="" autocomplete="off">
                                            <label for="giftName" class="focus">Name</label>
                                            <span class="validation-icon"></span>
                                            <p class="validation-text"></p>
                                            <span class="validation-icon"></span>
                                            <p class="validation-text"></p>
                                        </fieldset>
                                        <fieldset>
                                            <input data-validation-type="phone" type="text" name="giftPhone" id="giftPhone" class="js--primary-phone-gift" required="" autocomplete="off">
                                            <label for="giftPhone">Phone No</label>
                                            <span class="validation-icon"></span>
                                            <p class="validation-text"></p>
                                            <span class="validation-icon"></span>
                                            <p class="validation-text"></p>
                                        </fieldset>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <fieldset>
                                                <select data-validation-type="select-menu" class="custom-select js-shipping-country" id="js--country-gift" name="giftCountry" required="">
                                                    <option value="1" data-lang-eng="Bangladesh" selected="">Bangladesh</option>
                                                    <option value="2" data-lang-eng="India">India</option>
                                                    <option value="6" data-lang-eng="Australia">Australia</option>
                                                    <option value="5" data-lang-eng="Canada">Canada</option>
                                                    <option value="9" data-lang-eng="China">China</option>
                                                    <option value="38" data-lang-eng="Denmark">Denmark</option>
                                                    <option value="29" data-lang-eng="Finland ">Finland </option>
                                                    <option value="20" data-lang-eng="France">France</option>
                                                    <option value="7" data-lang-eng="Germany">Germany</option>
                                                    <option value="39" data-lang-eng="Ireland">Ireland</option>
                                                    <option value="22" data-lang-eng="Italy">Italy</option>
                                                    <option value="23" data-lang-eng="Japan">Japan</option>
                                                    <option value="32" data-lang-eng="Kuwait">Kuwait</option>
                                                    <option value="15" data-lang-eng="Malaysia">Malaysia</option>
                                                    <option value="8" data-lang-eng="Netharlands">Netharlands</option>
                                                    <option value="37" data-lang-eng="New Zealand">New Zealand</option>
                                                    <option value="24" data-lang-eng="Norway">Norway</option>
                                                    <option value="18" data-lang-eng="Oman">Oman</option>
                                                    <option value="31" data-lang-eng="Poland">Poland</option>
                                                    <option value="26" data-lang-eng="Portugal">Portugal</option>
                                                    <option value="17" data-lang-eng="Qatar">Qatar</option>
                                                    <option value="14" data-lang-eng="Saudi Arabia">Saudi Arabia</option>
                                                    <option value="16" data-lang-eng="Singapore">Singapore</option>
                                                    <option value="19" data-lang-eng="South Korea">South Korea</option>
                                                    <option value="34" data-lang-eng="Spain">Spain</option>
                                                    <option value="27" data-lang-eng="Sweden">Sweden</option>
                                                    <option value="28" data-lang-eng="Switzerland ">Switzerland </option>
                                                    <option value="21" data-lang-eng="Thailand">Thailand</option>
                                                    <option value="13" data-lang-eng="United Arab Emirates">United Arab Emirates</option>
                                                    <option value="10" data-lang-eng="United Kingdom">United Kingdom</option>
                                                    <option value="4" data-lang-eng="USA">USA</option>
                                                </select>
                                                <span class="validation-icon"></span>
                                                <p class="validation-text"></p>
                                                <span class="validation-icon"></span>
                                                <p class="validation-text"></p>
                                            </fieldset>
                                            <fieldset class="group">
                                                <select data-validation-type="select-menu" class="custom-select " id="js--city-gift" name="giftCity" required="">
                                                    <option selected="" disabled="" value="">Select City</option>
                                                    <option value="2" data-lang-eng="Dhaka">ঢাকা</option>
                                                    <option value="4" data-lang-eng="Rajshahi">রাজশাহী</option>
                                                </select>
                                                <span class="validation-icon"></span>
                                                <p class="validation-text"></p>
                                                <span class="validation-icon"></span>
                                                <p class="validation-text"></p>
                                            </fieldset>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <fieldset class="group">
                                                <select data-validation-type="select-menu" class="custom-select" id="js--area-gift" name="giftArea" required="">
                                                    <option selected="" disabled="" value="">Select Area</option>
                                                </select>
                                                <span class="validation-icon"></span>
                                                <p class="validation-text"></p>
                                                <span class="validation-icon"></span>
                                                <p class="validation-text"></p>
                                            </fieldset>
                                            <fieldset class="group" style="display: none;">
                                                <select data-validation-type="select-menu" class="custom-select" id="js--zone-gift" name="giftZoneId">
                                                    <option selected="" disabled="" value="">Select Zone</option>
                                                </select>
                                                <span class="validation-icon"></span>
                                                <p class="validation-text"></p>
                                                <span class="validation-icon"></span>
                                                <p class="validation-text"></p>
                                            </fieldset>
                                        </div>
                                        <fieldset>
                                            <textarea data-validation-type="address" name="giftAddress" id="giftAddress" required=""></textarea>
                                            <label for="giftAddress">Address</label>
                                            <span class="validation-icon"></span>
                                            <p class="validation-text"></p>
                                            <span class="validation-icon"></span>
                                            <p class="validation-text"></p>
                                        </fieldset>
                                        <div class="d-flex justify-content-between">
                                            <fieldset class="group">
                                                <select data-validation-type="select-menu" class="custom-select" name="giftFor">
                                                    <option value="" disabled="" selected="">Select Occasion</option>
                                                    <option value="Birthday">Birthday</option>
                                                    <option value="Anniversary">Anniversary</option>
                                                    <option value="Friendship">Friendship</option>
                                                    <option value="Others">Others</option>
                                                </select>
                                                <span class="validation-icon"></span>
                                                <p class="validation-text"></p>
                                                <span class="validation-icon"></span>
                                                <p class="validation-text"></p>
                                            </fieldset>
                                            <fieldset class="group date">
                                                <input data-validation-type="date" type="text" name="giftDate" id="js--gift-date" autocomplete="off">
                                                <label for="giftDate">Deliver Date</label>
                                                <span class="validation-icon"></span>
                                                <p class="validation-text"></p>
                                                <span class="validation-icon"></span>
                                                <p class="validation-text"></p>
                                            </fieldset>
                                        </div>
                                        <fieldset class="mb-2">
                                            <textarea data-validation-type="address" name="giftMass" id="js--gift-message" maxlength="500"></textarea>
                                            <label for="giftMass">Gift Message</label>
                                            <span class="validation-icon"></span>
                                            <p class="validation-text"></p>
                                            <span class="validation-icon"></span>
                                            <p class="validation-text"></p>
                                        </fieldset>
                                        <p>
                                            <span id="js--character-count-message">500</span> Character(s) Remaining
                                        </p>
                                    </div>
                                </section>
                            </div>
                            <p class="shipping-happy-return">
                                <img src="/static/200/images/svg/happy-return-big.svg" alt="return"> Happy Return <span>(7 days return facility)</span>
                            </p>
                            <div class="content__wrapper--payment">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-start align-items-center">
                                        <h3 class="card-title mb-0">Payment Method</h3>
                                        <p class="card-subtitle">(Please select only one! payment method)</p>
                                    </div>
                                    <div class="card-body pt-2">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-4">
                                            <div class="payment-item card form-check mt-3">
                                                <input type="radio" class="form-check-input" name="ptype" id="nagad" value="17" required="">
                                                <label class="form-check-label" for="nagad">
                                                    <img src="/static/200/images/Nagad-Logo.png" class="nagad-image" alt="nagad">
                                                </label>
                                            </div>
                                            <div class="payment-item card form-check mt-3">
                                                <input type="radio" class="form-check-input" name="ptype" id="ssl" value="8" required="">
                                                <label class="form-check-label" for="ssl">
                                                    <img src="/static/200/images/icon-ssl.png" class="ssl-image" alt="ssl">
                                                </label>
                                            </div>
                                            <div class="payment-item card card form-check mt-3">
                                                <input type="radio" class="form-check-input" name="ptype" id="bkash" value="1" required="">
                                                <label class="form-check-label" for="bkash">
                                                    <img src="/static/200/images/bkash.png" class="bkash-image" alt="bkash">
                                                </label>
                                            </div>
                                            <div class="payment-item card form-check mt-3">
                                                <input type="radio" class="form-check-input" name="ptype" id="rocket" value="3" required="">
                                                <label class="form-check-label" for="rocket">
                                                    <img src="/static/200/images/rocket.png" width="60px" alt="rocket">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="payment-content">
                                        <strong>বি:দ্র:</strong> কিছু কিছু ক্ষেত্রে আপনার অর্ডারে থাকা বই/পণ্যের মূল্য প্রকাশক/সরবরাহকারীর পক্ষ থেকে বিভিন্ন কারণে পরিবর্তন হতে পারে। এছাড়া আপনার অর্ডারের বই/পণ্য প্রকাশক/ সরবরাহকারীর কাছে নাও থাকতে পারে। এই ধরণের অনাকাঙ্ক্ষিত বিষয়গুলোর জন্য আমরা দুঃখিত ও ক্ষমাপ্রার্থী।
                                    </p>
                                    <div class="text-right border-top">
                                        <span class="text-danger js--require-msg mr-3 "></span>
                                        <button type="submit" value="Confirm Order" class="btn btn-confirm-payment" id="js--confirm-order";">
                                            <span>Confirm Order</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="_tk" id="_tk" value="xmimTePagIXz5r_gaCc2obYImA1N74eo2PoCdNSbyeA">
                        </form>
                    </div>
                    <div class="sidebar__summary" style="top: 116px;">
                        <div class="card">
                            <div class="card-header">
                                <h1 class="card-title mb-0">Checkout Summary</h1>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>Subtotal</td>
                                            <td class="text-right" id="subtotal">210 TK.</td>
                                        </tr>
                                        <tr style="display: none" id="wrappertr2">
                                            <td id="wrappertext2">Gift Wrap</td>
                                            <td class="text-right" id="wrapper2">20 TK. </td>
                                        </tr>
                                        <tr>
                                            <td id="shippingText">Shipping </td>
                                            <td class="text-right" id="shipping">50 TK. </td>
                                        </tr>
                                        <tr>
                                            <td>Total</td>
                                            <td class="text-right" id="total">260 TK. </td>
                                        </tr>
                                        <tr id="payabletr">
                                            <td class="font-weight-bold">Payable Total</td>
                                            <td class="text-right font-weight-bold" id="payable">260 TK. </td>
                                        </tr>
                                        <tr style="display: none" id="payabletr2">
                                            <td class="font-weight-bold">Payable Total</td>
                                            <td class="text-right font-weight-bold" id="payable2"></td>
                                        </tr>
                                        <tr style="">
                                            <td colspan="2" class="gift-wrap">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="gift-checkbox">
                                                    <label for="gift-checkbox" class="custom-control-label"> Gift Wrap for Tk. 20 </label>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer"> You are Saving 30% </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>';

       

        return $output;
    }
}
