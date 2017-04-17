            <div class="col-sm-4">
                <div id="ChildVerticalTab_1"  class="cboxbottom">
                    <ul class="resp-tabs-list ver_1 small-border-radius decoration ">
                        <li class=""><a href ="{{ url('edit-profile') }}"><span class="icon icon-account-circle  "></span>Profile</a></li>
                        <li class="{{ ($activeTab==2)?'resp-tab-active':'' }}"><a href ="{{ url('reports') }}"><span class="icon icon-drive-document "></span>Reports</a></li>
                        <li class="{{ ($activeTab==3)?'resp-tab-active':'' }}"><a href ="{{ url('change-password') }}"><span class="icon icon-lock "></span>Change Password</a></li>
                        <li class="{{ ($activeTab==4)?'resp-tab-active':'' }}"><a href ="{{ url('setting-subscription') }}"><span class="icon icon-drive-form"></span>Subscription Details</a></li>
                        <li class="{{ ($activeTab==5)?'resp-tab-active':'' }}"><a href ="{{ url('setting-terms-conditions') }}"><span class="icon icon-text-document-black-interface-symbol"></span>Terms & Conditions</a></li>
                        <li><a href ="{{ url('logout') }}"><span class="icon icon-logout-web-button "></span>Logout</a></li>
                    </ul>
                </div>
            </div>
