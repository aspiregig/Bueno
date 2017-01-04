@extends('layouts.master')

@section('content')
    @include('partials/static_links_nav')

    <section class="static_page_sec faq_sec">

        <div class="container more">
            <div class="row">
                <div class="col-xs-12">

                    <div class="well padding-md">
                        <h3 class="normal_header">FAQs</h3>
                    </div> <!-- well ends -->

                    <div class="static_content col-xs-12">
                        <div class="main col-xs-12 col-md-9">
                            
                            <div class="panel-group" id="founder_accordion" role="tablist" aria-multiselectable="true">
                                <div class="panel dashed panel-default">
                                    <div class="panel-heading" role="tab" id="titleFounder">
                                        <h4 class="panel-title">
                                            <a role="button" class="accordian_link arrow_down collapsed" data-toggle="collapse" data-parent="#founder_accordion" href="#collapseFounder" aria-expanded="true" aria-controls="collapseFounder" class="full_width">
                                                Q. What is Bueno?
                                            </a>
                                        </h4>
                                    </div> <!-- panel-heading ends -->
                                    <div id="collapseFounder" class="panel-collapse collapse" role="tabpanel" aria-labelledby="titleFounder">
                                        <div class="panel-body">
                                           <p><strong>Ans:</strong> Bueno, is the global food hub serving the world on a platter right to your doorstep. Literally

translated &#39;Bueno&#39; means goodness and tasty in Spanish. And this remains our guiding mantra, we strive

to bring to you good food, good quality and good experience. Offering 7 cuisines and over 60+ dishes

and counting, you decide the cuisine and menu, Bueno chefs create magic in our star kitchen and

promise to deliver a delightful experience to you within 45 minutes.</p>
                                        </div> <!-- panel-body ends -->
                                    </div> <!-- panel-collapse ends -->
                                </div> <!-- panel ends -->
                            </div> <!-- panel-group ends -->

                            <div class="panel-group" id="founder_accordion2" role="tablist" aria-multiselectable="true">
                                <div class="panel dashed panel-default">
                                    <div class="panel-heading" role="tab" id="titleFounder2">
                                        <h4 class="panel-title">
                                            <a role="button" class="accordian_link arrow_down collapsed" data-toggle="collapse" data-parent="#founder_accordion2" href="#collapseFounder2" aria-expanded="true" aria-controls="collapseFounder" class="full_width">
                                                Q. Why should I use Bueno? There are enough and more food delivery companies.
                                            </a>
                                        </h4>
                                    </div> <!-- panel-heading ends -->
                                    <div id="collapseFounder2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="titleFounder">
                                        <div class="panel-body">
                                            <p><strong>Ans:</strong> Bueno prides itself in providing you a complete and immersive food experience. Everything that is

delivered to you is prepared in Bueno’s state-of- the-art kitchen. From the kitchen to your doorstep

everything is Bueno. Our team of star Chefs diligently work on a 50-point quality checklist from

procurement to storage to crafting to delivery which is completely owned and quality controlled by us.

We are so finicky and quality conscious that very soon we are even planning to roll out our own pasta

sheets.</p>
                                        </div> <!-- panel-body ends -->
                                    </div> <!-- panel-collapse ends -->
                                </div> <!-- panel ends -->
                            </div> <!-- panel-group ends -->

                            <div class="panel-group" id="founder_accordion3" role="tablist" aria-multiselectable="true">
                                <div class="panel dashed panel-default">
                                    <div class="panel-heading" role="tab" id="titleFounder3">
                                        <h4 class="panel-title">
                                            <a role="button" class="accordian_link arrow_down collapsed" data-toggle="collapse" data-parent="#founder_accordion3" href="#collapseFounder3" aria-expanded="true" aria-controls="collapseFounder" class="full_width">
                                                Q.  Why should I use the App or the site to order instead of calling in?
                                            </a>
                                        </h4>
                                    </div> <!-- panel-heading ends -->
                                    <div id="collapseFounder3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="titleFounder">
                                        <div class="panel-body">
                                            <p>Well, because dinosaurs got extinct way back. Why would you want to waste time getting on the

phone when all it takes is a couple of taps on our very intelligent and intuitive app. Download the app,

check out the latest offers, choose your course and payment method and you are done.</p>
                                        </div> <!-- panel-body ends -->
                                    </div> <!-- panel-collapse ends -->
                                </div> <!-- panel ends -->
                            </div> <!-- panel-group ends -->
                            <div class="panel-group" id="founder_accordion4" role="tablist" aria-multiselectable="true">
                                <div class="panel dashed panel-default">
                                    <div class="panel-heading" role="tab" id="titleFounder4">
                                        <h4 class="panel-title">
                                            <a role="button" class="accordian_link arrow_down collapsed" data-toggle="collapse" data-parent="#founder_accordion4" href="#collapseFounder4" aria-expanded="true" aria-controls="collapseFounder" class="full_width">
                                                Q.  How does the App work?
                                            </a>
                                        </h4>
                                    </div> <!-- panel-heading ends -->
                                    <div id="collapseFounder4" class="panel-collapse collapse" role="tabpanel" aria-labelledby="titleFounder">
                                        <div class="panel-body">
                                            <p><strong>Ans:</strong> The entire operations runs on a proprietary technology backbone built by our own tech team to ensure: 

- seamlessness of ordering through our site and apps (which detects and stores your location, letting an

order go through only if the logistics allow our 45 minute delivery promise)

- quick order processing with complete process automation from order receipt to processing to exit 

- ideal delivery enablement with each rider having a locked GPS smartphone which runs only our

delivery app for seamless delivery to the GPS coordinates that was picked from / entered by the

customer ordering phone.</p>
                                        </div> <!-- panel-body ends -->
                                    </div> <!-- panel-collapse ends -->
                                </div> <!-- panel ends -->
                            </div> <!-- panel-group ends -->
                            <div class="panel-group" id="founder_accordion5" role="tablist" aria-multiselectable="true">
                                <div class="panel dashed panel-default">
                                    <div class="panel-heading" role="tab" id="titleFounder5">
                                        <h4 class="panel-title">
                                            <a role="button" class="accordian_link arrow_down collapsed" data-toggle="collapse" data-parent="#founder_accordion5" href="#collapseFounder5" aria-expanded="true" aria-controls="collapseFounder" class="full_width">
                                                Q. How do you ensure quality, standardization, consistency and value?
                                            </a>
                                        </h4>
                                    </div> <!-- panel-heading ends -->
                                    <div id="collapseFounder5" class="panel-collapse collapse" role="tabpanel" aria-labelledby="titleFounder">
                                        <div class="panel-body">
                                            <p><strong>Ans:</strong> We have a set base of raw material suppliers who are thoroughly inspected by us at sign up and

also go through periodic quality checks. Our kitchen and operations are automated and standardized so

no chance of errors creeping in. Food is completely made in our kitchen, by our chefs, packed at the

correct temperatures and delivered by our own dedicated delivery team. We don’t believe in leaving

anything to chance. We promise to deliver consistent value by offering affordable, value-for- money

superior quality meals.</p>
                                        </div> <!-- panel-body ends -->
                                    </div> <!-- panel-collapse ends -->
                                </div> <!-- panel ends -->
                            </div> <!-- panel-group ends -->
                            <div class="panel-group" id="founder_accordion55" role="tablist" aria-multiselectable="true">
                                <div class="panel dashed panel-default">
                                    <div class="panel-heading" role="tab" id="titleFounder">
                                        <h4 class="panel-title">
                                            <a role="button" class="accordian_link arrow_down collapsed" data-toggle="collapse" data-parent="#founder_accordion55" href="#collapseFounder55" aria-expanded="true" aria-controls="collapseFounder" class="full_width">
                                                Q.  How do I make payments for the food that I order?
                                            </a>
                                        </h4>
                                    </div> <!-- panel-heading ends -->
                                    <div id="collapseFounder55" class="panel-collapse collapse" role="tabpanel" aria-labelledby="titleFounder">
                                        <div class="panel-body">
                                            <p><strong>Ans:</strong> There are various convenient options available like Credit/Debit card, E-Wallets and Cash on

Delivery.</p>
                                        </div> <!-- panel-body ends -->
                                    </div> <!-- panel-collapse ends -->
                                </div> <!-- panel ends -->
                            </div> <!-- panel-group ends -->
                            <div class="panel-group" id="founder_accordion6" role="tablist" aria-multiselectable="true">
                                <div class="panel dashed panel-default">
                                    <div class="panel-heading" role="tab" id="titleFounder6">
                                        <h4 class="panel-title">
                                            <a role="button" class="accordian_link arrow_down collapsed" data-toggle="collapse" data-parent="#founder_accordion6" href="#collapseFounder6" aria-expanded="true" aria-controls="collapseFounder" class="full_width">
                                                Q.  What address should I use for placing orders? Can it be my office address?
                                            </a>
                                        </h4>
                                    </div> <!-- panel-heading ends -->
                                    <div id="collapseFounder6" class="panel-collapse collapse" role="tabpanel" aria-labelledby="titleFounder">
                                        <div class="panel-body">
                                            <p><strong>Ans:</strong>Yes, it can be your office address or your home address. Our App provides a feature to add multiple

addresses, so where ever you are, you don&#39;t miss good food. 
</p>
                                        </div> <!-- panel-body ends -->
                                    </div> <!-- panel-collapse ends -->
                                </div> <!-- panel ends -->
                            </div> <!-- panel-group ends -->
                            <div class="panel-group" id="founder_accordion7" role="tablist" aria-multiselectable="true">
                                <div class="panel dashed panel-default">
                                    <div class="panel-heading" role="tab" id="titleFounder7">
                                        <h4 class="panel-title">
                                            <a role="button" class="accordian_link arrow_down collapsed" data-toggle="collapse" data-parent="#founder_accordion7" href="#collapseFounder7" aria-expanded="true" aria-controls="collapseFounder" class="full_width">
                                                Q.  Is there a guaranteed delivery time?
                                            </a>
                                        </h4>
                                    </div> <!-- panel-heading ends -->
                                    <div id="collapseFounder7" class="panel-collapse collapse" role="tabpanel" aria-labelledby="titleFounder">
                                        <div class="panel-body">
                                            <p><strong>Ans:</strong> Yes, we promise a 45 minute delivery time, unless the skies fall down. We don’t take orders if we

cannot stick to our promised delivery time. We would rather not do something if we cannot do it

exceptionally well. We have our delivery team with each rider having a locked GPS smartphone which

runs only our delivery app for seamless delivery to the GPS coordinates that was picked from / entered

by the customer ordering phone. You can track your order in real time.</p>
                                        </div> <!-- panel-body ends -->
                                    </div> <!-- panel-collapse ends -->
                                </div> <!-- panel ends -->
                            </div> <!-- panel-group ends -->
                            <div class="panel-group" id="founder_accordion8" role="tablist" aria-multiselectable="true">
                                <div class="panel dashed panel-default">
                                    <div class="panel-heading" role="tab" id="titleFounder8">
                                        <h4 class="panel-title">
                                            <a role="button" class="accordian_link arrow_down collapsed" data-toggle="collapse" data-parent="#founder_accordion8" href="#collapseFounder8" aria-expanded="true" aria-controls="collapseFounder" class="full_width">
                                                Q. What do I do in case there is some problem with my order?
                                            </a>
                                        </h4>
                                    </div> <!-- panel-heading ends -->
                                    <div id="collapseFounder8" class="panel-collapse collapse" role="tabpanel" aria-labelledby="titleFounder">
                                        <div class="panel-body">
                                            <p><strong>Ans:</strong> You may contact Bueno Care at {{ config('bueno.site.phone') }} or write to us at info@bueno.kitchen for immediate

assistance by our friendly and helpful executives.</p>
                                        </div> <!-- panel-body ends -->
                                    </div> <!-- panel-collapse ends -->
                                </div> <!-- panel ends -->
                            </div> <!-- panel-group ends -->
                            <div class="panel-group" id="founder_accordion9" role="tablist" aria-multiselectable="true">
                                <div class="panel dashed panel-default">
                                    <div class="panel-heading" role="tab" id="titleFounder9">
                                        <h4 class="panel-title">
                                            <a role="button" class="accordian_link arrow_down collapsed" data-toggle="collapse" data-parent="#founder_accordion9" href="#collapseFounder9" aria-expanded="true" aria-controls="collapseFounder" class="full_width">
                                                Q.  Do I need to tip the delivery boys?
                                            </a>
                                        </h4>
                                    </div> <!-- panel-heading ends -->
                                    <div id="collapseFounder9" class="panel-collapse collapse" role="tabpanel" aria-labelledby="titleFounder">
                                        <div class="panel-body">
                                            <p><strong> Ans:</strong> No, you do not need to tip the delivery boys.</p>
                                        </div> <!-- panel-body ends -->
                                    </div> <!-- panel-collapse ends -->
                                </div> <!-- panel ends -->
                            </div> <!-- panel-group ends -->
                            <div class="panel-group" id="founder_accordion10" role="tablist" aria-multiselectable="true">
                                <div class="panel dashed panel-default">
                                    <div class="panel-heading" role="tab" id="titleFounder10">
                                        <h4 class="panel-title">
                                            <a role="button" class="accordian_link arrow_down collapsed" data-toggle="collapse" data-parent="#founder_accordion10" href="#collapseFounder10" aria-expanded="true" aria-controls="collapseFounder" class="full_width">
                                                Q.  Why is the cell phone number mandatory?
                                            </a>
                                        </h4>
                                    </div> <!-- panel-heading ends -->
                                    <div id="collapseFounder10" class="panel-collapse collapse" role="tabpanel" aria-labelledby="titleFounder">
                                        <div class="panel-body">
                                            <p><strong>Ans:</strong> Phone number is mandatory for us so that the delivery boy can call you to confirm or clarify your

order or address. Also, in case something that you have ordered is not available or if the order is going

to be delayed under some circumstances, we can let you know over the phone. So please provide us

with a cell number where you would be available.</p>
                                        </div> <!-- panel-body ends -->
                                    </div> <!-- panel-collapse ends -->
                                </div> <!-- panel ends -->
                            </div> <!-- panel-group ends -->
                            <div class="panel-group" id="founder_accordion11" role="tablist" aria-multiselectable="true">
                                <div class="panel dashed panel-default">
                                    <div class="panel-heading" role="tab" id="titleFounder11">
                                        <h4 class="panel-title">
                                            <a role="button" class="accordian_link arrow_down collapsed" data-toggle="collapse" data-parent="#founder_accordion11" href="#collapseFounder11" aria-expanded="true" aria-controls="collapseFounder" class="full_width">
                                                Q.  I don&#39;t have internet access. Can I still use your services?
                                            </a>
                                        </h4>
                                    </div> <!-- panel-heading ends -->
                                    <div id="collapseFounder11" class="panel-collapse collapse" role="tabpanel" aria-labelledby="titleFounder">
                                        <div class="panel-body">
                                            <p><strong>Ans:</strong> Yes, we do offer phone support - you can call our customer care executives at {{ config('bueno.site.phone') }}

Although your order can be processed more efficiently if you order on App / online, we do understand

that connectivity might be an issue so we are glad to provide you with phone support.</p>
                                        </div> <!-- panel-body ends -->
                                    </div> <!-- panel-collapse ends -->
                                </div> <!-- panel ends -->
                            </div> <!-- panel-group ends -->
                            <div class="panel-group" id="founder_accordion12" role="tablist" aria-multiselectable="true">
                                <div class="panel dashed panel-default">
                                    <div class="panel-heading" role="tab" id="titleFounder12">
                                        <h4 class="panel-title">
                                            <a role="button" class="accordian_link arrow_down collapsed" data-toggle="collapse" data-parent="#founder_accordion12" href="#collapseFounder12" aria-expanded="true" aria-controls="collapseFounder" class="full_width">
                                                Q. Why is the phone number mandatory?
                                            </a>
                                        </h4>
                                    </div> <!-- panel-heading ends -->
                                    <div id="collapseFounder12" class="panel-collapse collapse" role="tabpanel" aria-labelledby="titleFounder">
                                        <div class="panel-body">
                                            <p><strong>Ans:</strong> Phone number is mandatory for us so that the delivery boy can call you to confirm or clarify your order or address. Also, in case something that you have ordered is not available or if the order is going to be delayed under some circumstances, we can let you know over the phone. So please provide us with a number where you would be available (preferably your cell phone number).</p>
                                        </div> <!-- panel-body ends -->
                                    </div> <!-- panel-collapse ends -->
                                </div> <!-- panel ends -->
                            </div> <!-- panel-group ends -->
                            <div class="panel-group" id="founder_accordion13" role="tablist" aria-multiselectable="true">
                                <div class="panel dashed panel-default">
                                    <div class="panel-heading" role="tab" id="titleFounder13">
                                        <h4 class="panel-title">
                                            <a role="button" class="accordian_link arrow_down collapsed" data-toggle="collapse" data-parent="#founder_accordion13" href="#collapseFounder13" aria-expanded="true" aria-controls="collapseFounder" class="full_width">
                                                Q.  I don't have internet access. Can I still use your services?
                                            </a>
                                        </h4>
                                    </div> <!-- panel-heading ends -->
                                    <div id="collapseFounder13" class="panel-collapse collapse" role="tabpanel" aria-labelledby="titleFounder">
                                        <div class="panel-body">
                                            <p><strong>Ans:</strong> Yes, we do offer phone support - you can call our customer care executives at {{ config('bueno.site.phone') }}. Although your order can be processed more efficiently if you order online, we do understand that connectivity might be an issue so we are glad to provide you with phone support.</p>
                                        </div> <!-- panel-body ends -->
                                    </div> <!-- panel-collapse ends -->
                                </div> <!-- panel ends -->
                            </div> <!-- panel-group ends -->
                            <div class="panel-group" id="founder_accordion14" role="tablist" aria-multiselectable="true">
                                <div class="panel dashed panel-default">
                                    <div class="panel-heading" role="tab" id="titleFounder14">
                                        <h4 class="panel-title">
                                            <a role="button" class="accordian_link arrow_down collapsed" data-toggle="collapse" data-parent="#founder_accordion14" href="#collapseFounder14" aria-expanded="true" aria-controls="collapseFounder" class="full_width">
                                                Q.  What are your working days?
                                            </a>
                                        </h4>
                                    </div> <!-- panel-heading ends -->
                                    <div id="collapseFounder14" class="panel-collapse collapse" role="tabpanel" aria-labelledby="titleFounder">
                                        <div class="panel-body">
                                            <p><strong>Ans:</strong> We work all days, from 9 AM – 4 AM. Soon 24*7 delivery across Gurgaon. </p>
                                        </div> <!-- panel-body ends -->
                                    </div> <!-- panel-collapse ends -->
                                </div> <!-- panel ends -->
                            </div> <!-- panel-group ends -->
                            <div class="panel-group" id="founder_accordion15" role="tablist" aria-multiselectable="true">
                                <div class="panel dashed panel-default">
                                    <div class="panel-heading" role="tab" id="titleFounder15">
                                        <h4 class="panel-title">
                                            <a role="button" class="accordian_link arrow_down collapsed" data-toggle="collapse" data-parent="#founder_accordion15" href="#collapseFounder15" aria-expanded="true" aria-controls="collapseFounder" class="full_width">
                                                Q. Where all do you deliver right now?
                                            </a>
                                        </h4>
                                    </div> <!-- panel-heading ends -->
                                    <div id="collapseFounder15" class="panel-collapse collapse" role="tabpanel" aria-labelledby="titleFounder">
                                        <div class="panel-body">
                                            <p><strong>Ans:</strong> Gurgaon</p>
                                        </div> <!-- panel-body ends -->
                                    </div> <!-- panel-collapse ends -->
                                </div> <!-- panel ends -->
                            </div> <!-- panel-group ends -->
                        </div> <!-- main ends -->
                    </div> <!-- static_content ends -->
                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->

    </section> <!-- <section> ends -->

@stop