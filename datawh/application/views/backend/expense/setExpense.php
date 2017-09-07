<!DOCTYPE html>
<html lang="zh-CN">
  <head>
        <?php require_once 'application/views/backend/htmlhead.php'; ?>   
        <!-- 首页对应js -->
        <script src="application/views/backend/expense/setExpense.js"></script>
        <script src="application/views/backend/expense/productDialog.js"></script>
        <script src="application/views/backend/expense/storeDialog.js"></script>
        <script src="application/views/backend/expense/paymentDialog.js"></script>
        
        <!-- datetimepicker -->
        <script src="public/plugins/datetimepicker/bootstrap-datetimepicker.min.js"></script>
        <link href="public/plugins/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet">
        
        <!-- validator -->
        <script src="public/plugins/validator/validator.min.js"></script>
        
        <!-- combobox -->
        <script src="public/plugins/combobox/bootstrap-combobox-1.1.6.js"></script>
        <link href="public/plugins/combobox/bootstrap-combobox-1.1.6.css" rel="stylesheet">
        
        <!-- multiselect -->
        <script src="public/plugins/multiselect/bootstrap-multiselect-2.0.js"></script>
        <link href="public/plugins/multiselect/bootstrap-multiselect-2.0.css" rel="stylesheet">
        
        <!-- text box -->
        <script src="public/plugins/uiwidget/customTextWidget.js"></script>
        <script src="public/plugins/uiwidget/customTextareaWidget.js"></script>
        <script src="public/plugins/uiwidget/customSelectBoxWidget.js"></script>
        <script src="public/plugins/uiwidget/customDatetimeWidget.js"></script>
  </head>
  <body>
    <div id="theme-wrapper">
        <?php require_once 'application/views/backend/header.php'; ?> 
        <div id="page-wrapper" class="container">
            <div class="row">
                <div id="nav-col">
                    <?php require_once 'application/views/backend/menu.php'; ?> 
                </div>
                <div id="content-wrapper">
                    <div class="row" style="opacity: 1;">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <ol class="breadcrumb">
                                        <li><a href="index.php?p=backend&c=Index&a=index">Home</a></li>
                                        <li class="active"><span>Expense</span></li>
                                    </ol>
                                    <h1 id="setExpense-title" >添加消费项</h1>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="main-box">
                                        <header class="main-box-header clearfix">
                                            <h2></h2>
                                        </header>
                                        <div class="main-box-body clearfix">
                                            <form  id="main-group" data-toggle="validator" role="form">
<!--                                            <div class="form-group has-success">
                                                <label class="control-label" for="inputSuccess">Input with success</label>
                                                <input type="text" class="form-control" id="inputSuccess">
                                                <span class="help-block"><i class="icon-ok-sign"></i> Success message</span>
                                            </div>
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="inputWarning">Input with warning</label>
                                                <input type="text" class="form-control" id="inputWarning">
                                                <span class="help-block"><i class="icon-warning-sign"></i> Warning message</span>
                                            </div>
                                            <div class="form-group has-error">
                                                <label class="control-label" for="inputError">Input with error</label>
                                                <input type="text" class="form-control" id="inputError">
                                                <span class="help-block"><i class="icon-remove-sign"></i> Error message</span>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputFile">Disabled input</label>
                                                <input class="form-control" id="exampleInputFile" type="text" placeholder="Disabled input here..." disabled="">
                                            </div>
                                            <div class="form-group">
                                                <label>Checkboxes</label>
                                                <div class="checkbox-nice">
                                                    <input type="checkbox" id="checkbox-1" checked="checked">
                                                    <label for="checkbox-1">
                                                        Option one is this and that—be sure to include why it's great
                                                    </label>
                                                </div>
                                                <div class="checkbox-nice">
                                                    <input type="checkbox" id="checkbox-2">
                                                    <label for="checkbox-2">
                                                        Option two is this and that—be sure to include why it's great
                                                    </label>
                                                </div>
                                                <div class="checkbox-nice">
                                                    <input type="checkbox" id="checkbox-3">
                                                    <label for="checkbox-3">
                                                        Option three is this and that—be sure to include why it's great
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Radio inputs</label>
                                                <div class="radio">
                                                    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked="">
                                                    <label for="optionsRadios1">
                                                        Option one is this and that—be sure to include why it's great
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                                                    <label for="optionsRadios2">
                                                        Option two can be something else and selecting it will deselect option one
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Inline checkboxes</label>
                                                <br>
                                                <div class="checkbox-nice checkbox-inline">
                                                    <input type="checkbox" id="checkbox-inl-1">
                                                    <label for="checkbox-inl-1">
                                                        1
                                                    </label>
                                                </div>
                                                <div class="checkbox-nice checkbox-inline">
                                                    <input type="checkbox" id="checkbox-inl-2">
                                                    <label for="checkbox-inl-2">
                                                        2
                                                    </label>
                                                </div>
                                                <div class="checkbox-nice checkbox-inline">
                                                    <input type="checkbox" id="checkbox-inl-3">
                                                    <label for="checkbox-inl-3">
                                                        3
                                                    </label>
                                                </div>
                                            </div>
                                            <h3><span>Checkbox buttons</span></h3>
                                            <div class="btn-group" data-toggle="buttons">
                                                <label class="btn btn-primary">
                                                    <input type="checkbox"> Option 1
                                                </label>
                                                <label class="btn btn-primary">
                                                    <input type="checkbox"> Option 2
                                                </label>
                                                <label class="btn btn-primary">
                                                    <input type="checkbox"> Option 3
                                                </label>
                                            </div>
                                            <h3><span>Radio buttons</span></h3>
                                            <div class="btn-group" data-toggle="buttons">
                                                <label class="btn btn-primary">
                                                    <input type="radio" name="options" id="option1"> Option 1
                                                </label>
                                                <label class="btn btn-primary">
                                                    <input type="radio" name="options" id="option2"> Option 2
                                                </label>
                                                <label class="btn btn-primary">
                                                    <input type="radio" name="options" id="option3"> Option 3
                                                </label>
                                            </div>
                                            <br><br>-->
                                            </form>
                                        </div>
<!--                                        <div class="main-box-body clearfix">
                                            <form role="form">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Email address</label>
                                                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputPassword1">Password</label>
                                                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleTextarea">Textarea</label>
                                                    <textarea class="form-control" id="exampleTextarea" rows="3"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleTooltip">Input with Tooltip</label>
                                                    <input type="text" class="form-control" id="exampleTooltip" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="very nice tooltip about this field">
                                                </div>
                                                <div class="form-group">
                                                    <label>Default Select</label>
                                                    <select class="form-control">
                                                        <option>1</option>
                                                        <option>2</option>
                                                        <option>3</option>
                                                        <option>4</option>
                                                        <option>5</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Multiple select</label>
                                                    <select multiple="" class="form-control">
                                                        <option>1</option>
                                                        <option>2</option>
                                                        <option>3</option>
                                                        <option>4</option>
                                                        <option>5</option>
                                                    </select>
                                                </div>
                                                <div class="form-group form-group-select2">
                                                    <label>Enhanced Select</label>
                                                    <div class="select2-container" id="s2id_sel2" style="width:300px"><a href="javascript:void(0)" onclick="return false;" class="select2-choice" tabindex="-1">   <span class="select2-chosen">United States</span><abbr class="select2-search-choice-close"></abbr>   <span class="select2-arrow"><b></b></span></a><input class="select2-focusser select2-offscreen" type="text" id="s2id_autogen1"></div><select style="width:300px" id="sel2" tabindex="-1" class="select2-offscreen">
                                                        <option value="United States">United States</option>
                                                        <option value="United Kingdom">United Kingdom</option>
                                                        <option value="Afghanistan">Afghanistan</option>
                                                        <option value="Albania">Albania</option>
                                                        <option value="Algeria">Algeria</option>
                                                        <option value="American Samoa">American Samoa</option>
                                                        <option value="Andorra">Andorra</option>
                                                        <option value="Angola">Angola</option>
                                                        <option value="Anguilla">Anguilla</option>
                                                        <option value="Antarctica">Antarctica</option>
                                                        <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                                        <option value="Argentina">Argentina</option>
                                                        <option value="Armenia">Armenia</option>
                                                        <option value="Aruba">Aruba</option>
                                                        <option value="Australia">Australia</option>
                                                        <option value="Austria">Austria</option>
                                                        <option value="Azerbaijan">Azerbaijan</option>
                                                        <option value="Slovakia">Slovakia</option>
                                                    </select>
                                                </div>
                                                <div class="form-group form-group-select2">
                                                    <label>Multi-Value Select Boxes</label>
                                                    <div class="select2-container select2-container-multi" id="s2id_sel2Multi" style="width:300px"><ul class="select2-choices">  <li class="select2-search-field">    <input type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input select2-default" id="s2id_autogen2" style="width: 298px;">  </li></ul><div class="select2-drop select2-drop-multi select2-display-none">   <ul class="select2-results">   <li class="select2-no-results">No matches found</li></ul></div></div><select style="width:300px" id="sel2Multi" multiple="" tabindex="-1" class="select2-offscreen">
                                                        <option value="United States">United States</option>
                                                        <option value="United Kingdom">United Kingdom</option>
                                                        <option value="Afghanistan">Afghanistan</option>
                                                        <option value="Albania">Albania</option>
                                                        <option value="Algeria">Algeria</option>
                                                        <option value="American Samoa">American Samoa</option>
                                                        <option value="Andorra">Andorra</option>
                                                        <option value="Angola">Angola</option>
                                                        <option value="Anguilla">Anguilla</option>
                                                        <option value="Antarctica">Antarctica</option>
                                                        <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                                        <option value="Argentina">Argentina</option>
                                                        <option value="Armenia">Armenia</option>
                                                        <option value="Aruba">Aruba</option>
                                                        <option value="Australia">Australia</option>
                                                        <option value="Austria">Austria</option>
                                                        <option value="Azerbaijan">Azerbaijan</option>
                                                        <option value="Slovakia">Slovakia</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleAutocompleteSimple">Autocomplete</label>
                                                    <span class="twitter-typeahead" style="position: relative; display: inline-block; direction: ltr;"><input class="tt-hint" type="text" autocomplete="off" spellcheck="off" disabled="" style="position: absolute; top: 0px; left: 0px; border-color: transparent; box-shadow: none; background: none 0% 0% / auto repeat scroll padding-box padding-box rgb(255, 255, 255);"><input type="text" class="form-control tt-query" id="exampleAutocompleteSimple" placeholder="countries" autocomplete="off" spellcheck="false" dir="auto" style="position: relative; vertical-align: top; background-color: transparent;"><span style="position: absolute; left: -9999px; visibility: hidden; white-space: nowrap; font-family: &quot;Open Sans&quot;, sans-serif; font-size: 13px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; word-spacing: 0px; letter-spacing: 0px; text-indent: 0px; text-rendering: auto; text-transform: none;">c</span><span class="tt-dropdown-menu" style="position: absolute; top: 100%; left: 0px; z-index: 100; display: none; right: auto;"><div class="tt-dataset-0" style="display: none;"><span class="tt-suggestions" style="display: block;"></span></div></span></span>
                                                </div>
                                                <div class="form-group example-twitter-oss">
                                                    <label for="exampleAutocomplete">Autocomplete with templating</label>
                                                    <span class="twitter-typeahead" style="position: relative; display: inline-block;"><input class="tt-hint" type="text" autocomplete="off" spellcheck="off" disabled="" style="position: absolute; top: 0px; left: 0px; border-color: transparent; box-shadow: none; background: none 0% 0% / auto repeat scroll padding-box padding-box rgb(255, 255, 255);"><input type="text" class="form-control tt-query" id="exampleAutocomplete" placeholder="open source projects by Twitter" autocomplete="off" spellcheck="false" dir="auto" style="position: relative; vertical-align: top; background-color: transparent;"><span style="position: absolute; left: -9999px; visibility: hidden; white-space: nowrap; font-family: &quot;Open Sans&quot;, sans-serif; font-size: 13px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; word-spacing: 0px; letter-spacing: 0px; text-indent: 0px; text-rendering: auto; text-transform: none;"></span><span class="tt-dropdown-menu" style="position: absolute; top: 100%; left: 0px; z-index: 100; display: none;"></span></span>
                                                </div>
                                                <div class="form-group">
                                                    <label for="examplePwdMeter">Password strength meter (start typing...)</label>
                                                    <input type="password" class="form-control" id="examplePwdMeter" placeholder="Enter password" data-indicator="pwindicator">
                                                    <div id="pwindicator" class="pwdindicator">
                                                        <div class="bar"></div>
                                                        <div class="pwdstrength-label"></div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </body>
</html>