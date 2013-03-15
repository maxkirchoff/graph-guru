    <div id="postsView" style="display:none;">
        <div class="row">

        </div>
        <div class="row">
            <div id="postsViewHeader" class="span10 offset1">
                <div>
                    <h1>Overview by Post Type</h1>
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <td><strong>Post Type</strong></td>
                                <td>Photo</td>
                                <td>Video</td>
                                <td>Link</td>
                                <td>Status</td>
                            </tr>
                            <tr>
                                <td><strong>Average Score</strong></td>
                                <td class="photoScore"></td>
                                <td class="videoScore"></td>
                                <td class="linkScore"></td>
                                <td class="statusScore"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div id="posts" class="span10 offset1">
                <div id="postsWrapper">
                    <h1>Post Metrics</h1>
                    <div>
                        <table id="postGrid" class="table table-bordered datagrid">
                            <thead>
                            <tr>
                                <th>
                                    <span class="datagrid-header-title"><strong>Facebook Posts</strong></span>
                                    <div class="datagrid-header-right">
                                        <div class="select filter" data-resize="auto">
                                            <button data-toggle="dropdown" class="btn dropdown-toggle">
                                                <span class="dropdown-label"></span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li data-value="all" data-selected="true"><a href="#">All</a></li>
                                                <li data-value="photo"><a href="#">Photo Posts</a></li>
                                                <li data-value="link"><a href="#">Link Posts</a></li>
                                                <li data-value="video"><a href="#">Video Posts</a></li>
                                                <li data-value="status"><a href="#">Status Posts</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>
                                    <div class="datagrid-footer-left" style="display:none;">
                                        <div class="grid-controls">
                                                            <span>
                                                                <span class="grid-start"></span> -
                                                                <span class="grid-end"></span> of
                                                                <span class="grid-count"></span>
                                                            </span>
                                            <div class="select grid-pagesize" data-resize="auto">
                                                <button data-toggle="dropdown" class="btn dropdown-toggle">
                                                    <span class="dropdown-label"></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li data-value="5"><a href="#">5</a></li>
                                                    <li data-value="10" data-selected="true"><a href="#">10</a></li>
                                                    <li data-value="20"><a href="#">20</a></li>
                                                    <li data-value="50"><a href="#">50</a></li>
                                                    <li data-value="100"><a href="#">100</a></li>
                                                </ul>
                                            </div>
                                            <span>Per Page</span>
                                        </div>
                                    </div>
                                    <div class="datagrid-footer-right" style="display:none;">
                                        <div class="grid-pager">
                                            <button class="btn grid-prevpage"><i class="icon-chevron-left"></i></button>
                                            <span>Page</span>

                                            <div class="input-append dropdown combobox">
                                                <input class="span1" type="text">
                                                <button class="btn" data-toggle="dropdown"><i class="caret"></i></button>
                                                <ul class="dropdown-menu"></ul>
                                            </div>
                                            <span>of <span class="grid-pages"></span></span>
                                            <button class="btn grid-nextpage"><i class="icon-chevron-right"></i></button>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>