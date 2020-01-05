<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="toolbar">
                    <!--        Here you can write extra buttons/actions for the toolbar              -->
                </div>
                <table id="blog-table-view" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>{{__('backend.blog_background_header')}}</th>
                            <th width="35%">{{ __('backend.blog_title_header') }}</th>
                            <th>{{ __('backend.blog_categories_header') }}</th>
                            <th>{{ __('backend.blog_comments_header') }}</th>
                            <th width="25%" class="disabled-sorting text-right">{{ __('backend.blog_action_header') }}
                            </th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>{{__('backend.blog_background_header')}}</th>
                            <th width="35%">{{ __('backend.blog_title_header') }}</th>
                            <th>{{ __('backend.blog_categories_header') }}</th>
                            <th>{{ __('backend.blog_comments_header') }}</th>
                            <th class="disabled-sorting text-right">{{ __('backend.blog_action_header') }}</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <!-- end content-->
        </div>
        <!--  end card  -->
    </div>
    <!-- end col-md-12 -->
</div>
<!-- end row -->