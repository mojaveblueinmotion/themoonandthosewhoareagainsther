<div id="kt_header" class="header header-fixed">
    <div class="container-fluid d-flex align-items-stretch justify-content-between">
        {{-- @include('layouts.base.header-menu') --}}
        <div></div>

        <div class="topbar">
            <div class="dropdown dropdown-notification">
                @php
                    if (auth()->check()) {
                        $count = auth()->user()->notifications()->latest()->wherePivot('readed_at', null)->count();
                    } else {
                        $count = 0;
                    }
                @endphp
                <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
                    <div class="btn btn-icon btn-clean btn-dropdown btn-lg pulse pulse-success mr-1">
                        <i class="far fa-bell"></i>
                        <span class="pulse-ring"></span>
                    </div>
                    <div class="user-notification-badge {{ $count === 0 ? 'hide' : '' }}"
                        style="margin-top: -20px; margin-left: -20px; z-index: 11">
                        <span class="label label-light-danger label-pill label-inline mr-2">{{ $count }}</span>
                    </div>
                </div>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg m-0 p-0">
                    <div
                        class="d-flex flex-column flex-center bgi-size-cover bgi-no-repeat rounded-top bg-app py-10 opacity-80">
                        <h4 class="d-flex flex-center rounded-top">
                            <span class="text-white">User Notifications</span>
                            <span
                                class="user-notification-header btn btn-text btn-success btn-sm font-weight-bold btn-font-md {{ $count === 0 ? 'hide' : '' }} ml-2">
                                <span>{{ $count }} new</span>
                            </span>
                        </h4>
                    </div>
                    @if (auth()->check())
                        <div class="row row-paddingless">
                            <div class="col-12">
                                <div class="base-notification-wrapper" data-url="{{ rut('ajax.userNotification') }}"
                                    data-last-user-notification="{{ auth()->user()->getLastNotificationId() }}">
                                    @include('layouts.base.notification')
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- <div class="dropdown">
                <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px" aria-expanded="false">
                    <div class="btn btn-icon btn-clean btn-dropdown btn-lg mr-1">
                        @php
                            $flagEn = '226-united-states.svg';
                            $flagId = '004-indonesia.svg';
                            $icon = \App::getLocale() == 'en' ? $flagEn : $flagId;
                        @endphp
                        <img class="h-20px w-20px rounded-sm" src="{{ '/' . ('assets/media/svg/flags/' . $icon) }}"
                            alt="Image">
                    </div>
                </div>
                <div class="dropdown-menu dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right m-0 p-0">
                    <ul class="navi navi-hover py-4">
                        <li class="navi-item active">
                            <a href="{{ rut('setLang', 'id') }}" class="navi-link">
                                <span class="symbol symbol-20 mr-3">
                                    <img src="{{ '/' . ('assets/media/svg/flags/' . $flagId) }}" alt="Image">
                                </span>
                                <span class="navi-text">Indonesia</span>
                            </a>
                        </li>
                        <li class="navi-item">
                            <a href="{{ rut('setLang', 'en') }}" class="navi-link">
                                <span class="symbol symbol-20 mr-3">
                                    <img src="{{ '/' . ('assets/media/svg/flags/' . $flagEn) }}" alt="Image">
                                </span>
                                <span class="navi-text">English</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div> --}}

            @if (auth()->check())
                <div class="dropdown">
                    <div class="topbar-item" data-toggle="dropdown" data-offset="0px,0px" aria-expanded="false">
                        <div class="btn btn-icon btn-clean d-flex align-items-center btn-lg px-md-2 w-md-auto">
                            <span class="link-text font-weight-bolder font-size-base d-none d-md-inline mr-4">
                                {{ auth()->user()->name }}<br>
                                ({{ auth()->user()->roles_imploded }})
                            </span>
                            <div class="symbol ssymbol-lg-35 symbol-25 symbol-light-success symbol-circle shadow"
                                id="ctrlProfilePhoto" @if (!auth()->user()->image) style="display:none;" @endif>
                                <img class="profileImage" src="{{ '/' . auth()->user()->image_path }}" alt="Image">
                                <i class="symbol-badge symbol-badge-bottom bg-success"></i>
                            </div>
                            <span class="symbol symbol-lg-35 symbol-25 symbol-light-success"
                                id="ctrlProfilePhotoDefault"
                                @if (auth()->user()->image) style="display:none;" @endif>
                                <span class="symbol-label font-size-h5 font-weight-bold">
                                    {{ auth()->user()->name[0] }}
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg m-0 p-0 p-0">
                        <div class="d-flex align-items-center rounded-top p-8">
                            <div class="symbol symbol-md bg-light-primary mr-3 flex-shrink-0 shadow-sm">
                                <img class="profileImage" src="{{ '/' . auth()->user()->image_path }}" alt="Image">
                            </div>

                            <div class="text-dark flex-grow-1 font-size-h5 m-0 mr-3">
                                <div class="font-weight-bold">{{ auth()->user()->name }}</div>
                                <div class="text-muted font-size-12">{{ auth()->user()->roles_imploded }}</div>
                            </div>
                        </div>
                        <div class="separator separator-solid"></div>
                        <div class="navi navi-spacer-x-0 pt-5">
                            <a href="{{ rut('setting.profile.index') }}" class="navi-item base-content--replace px-8">
                                <div class="navi-link">
                                    <div class="navi-icon mr-2">
                                        <i class="flaticon2-calendar-3 text-success"></i>
                                    </div>
                                    <div class="navi-text">
                                        <div class="font-weight-bold">My Profile</div>
                                        <div class="text-muted">Account settings and more</div>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ rut('setting.profile.notification') }}"
                                class="navi-item base-content--replace px-8">
                                <div class="navi-link">
                                    <div class="navi-icon mr-2">
                                        <i class="flaticon2-rocket-1 text-danger"></i>
                                    </div>
                                    <div class="navi-text">
                                        <div class="font-weight-bold">My Notification</div>
                                        <div class="text-muted">All your notifications</div>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ rut('setting.profile.activity') }}"
                                class="navi-item base-content--replace px-8">
                                <div class="navi-link">
                                    <div class="navi-icon mr-2">
                                        <i class="flaticon2-rocket-1 text-danger"></i>
                                    </div>
                                    <div class="navi-text">
                                        <div class="font-weight-bold">My Activities</div>
                                        <div class="text-muted">All your activities</div>
                                    </div>
                                </div>
                            </a>

                            <div class="navi-separator mt-3"></div>
                            <div class="navi-footer float-right px-8 py-5">
                                <form action="<?= rut('logout') ?>" method="POST">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-light-primary font-weight-bold"
                                        id="signOutBtn">Sign Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
