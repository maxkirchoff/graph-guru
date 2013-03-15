    <div id="postsView" style="display:none;">
        <div class="row">

        </div>
        <div class="row">
            <div id="postsViewHeader" class="span12 offset1">
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
            <div id="posts" class="span12 offset1">
                <div id="postsWrapper">
                    <h1>Post Metrics</h1>
                    <div class="accordion" id="accordion2">
                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                                    <i class="icon-align-justify"></i> <strong>Modify Score Formula</strong>
                                </a>
                            </div>
                            <div id="collapseOne" class="accordion-body collapse in">
                                <div class="accordion-inner">
                                    <div class="span5">
                                        <div>
                                            <h5>Construct a custom score formula</h5>
                                            <p>Use the variable and operator guides to the left to construct a custom formula. You may also introduce standard numbers and organize your operations by wrapping them in parens '()'</p>
                                            <form id="formulaForm">
                                                <textarea style="width:100%" rows="10">(likes+comments+shares)/impressions</textarea><br />
                                                <input type="submit" value="Recalculate"/>
                                            </form>
                                        </div>
                                        <div>
                                            <h3></h3>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Measure</th>
                                                <th>Formula Variable</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>Likes</td>
                                                <td>likes</td>
                                            </tr>
                                            <tr>
                                                <td>Comments</td>
                                                <td>comments</td>
                                            </tr><tr>
                                                <td>Shares</td>
                                                <td>shares</td>
                                            </tr><tr>
                                                <td>Photo Views</td>
                                                <td>photo_views</td>
                                            </tr><tr>
                                                <td>Video Plays</td>
                                                <td>video_plays</td>
                                            </tr><tr>
                                                <td>Link Clicks</td>
                                                <td>link_clicks</td>
                                            </tr><tr>
                                                <td>Impressions</td>
                                                <td>impressions</td>
                                            </tr><tr>
                                                <td>Reach</td>
                                                <td>reach</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="span3">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Operation</th>
                                                <th>Operator</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>Addition</td>
                                                <td>+</td>
                                            </tr>
                                            <tr>
                                                <td>Subtraction</td>
                                                <td>-</td>
                                            </tr><tr>
                                                <td>Multiplication</td>
                                                <td>*</td>
                                            </tr><tr>
                                                <td>Division</td>
                                                <td>/</td>
                                            </tr><tr>
                                                <td>Order of Operations</td>
                                                <td>()</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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