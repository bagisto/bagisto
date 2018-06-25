<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="{{ asset('vendor/webkul/ui/assets/css/ui.css') }}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,500">
        
        <style type="text/css">
            body{
                margin: 45px;
                font-size: 14px;
                font-family: "Montserrat", sans-serif;
            }
            .styleguide-label{
                color: #7f7f7f;
                text-transform: uppercase;
                font-weight: 700;
                display: block;
                margin: 20px auto 10px;
            }
        
            .styleguide-wrapper{
                border: solid 1px #dadada;
                border-radius: 4px;
                padding:25px; 
            }
        
            pre {
                background: #ECEFF1;
                border-radius: 5px;
                font-family: monospace;
                color: #424242;
                padding: 20px;
                line-height: 20px;
                margin: 20px 0px 0px 0px;
            }
            * {
                box-sizing: border-box;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }

            *:focus {
                outline: none;
            }
            a:link,
            a:hover,
            a:visited,
            a:focus,
            a:active {
                text-decoration: none;
            }
        </style>
    </head>
    
    <body>
        <label class="styleguide-label">Buttons</label>
        <div class="styleguide-wrapper">
            <button class="btn btn-sm btn-primary">Button Small</button>
            <button class="btn btn-md btn-primary">Button Medium</button>
            <button class="btn btn-lg btn-primary">Button Large</button>
            <button class="btn btn-xl btn-primary">Button Extra Large</button>
        </div>


        <label class="styleguide-label">Buttons</label>
        <div class="styleguide-wrapper">
            <div class="form-container">
                <div class="control-group has-error">
                    <label for="">Input Field</label>
                    <input type="text" class="control"/>
                    <span class="control-info">This is control information</span>
                    <span class="control-error">This field is mandatory</span>
                </div>

                <div class="control-group">
                    <label for="">Input Field (Disabled)</label>
                    <input type="text" class="control" disabled="disabled"/>
                    <span class="control-info">This is control information</span>
                    <span class="control-error">This field is mandatory</span>
                </div>

                <div class="control-group">
                    <label for="">Select Field</label>
                    <select class="control">
                        <option value="1">Option 1</option>
                        <option value="1">Option 2</option>
                        <option value="1">Option 3</option>
                        <option value="1">Option 4</option>
                    </select>
                    <span class="control-info">This is control information</span>
                </div>

                <div class="control-group">
                    <label for="">Textarea Field</label>
                    <textarea class="control"></textarea>
                    <span class="control-info">This is control information</span>
                </div>

                <div class="control-group">
                    <label for="">Checkbox Field</label>
                    <span class="radio">
                        <input type="radio" id="radio1" name="radio"/>
                        <label class="radio-view" for="radio1"></label>
                        Radio Value 1
                    </span>

                    <span class="radio">
                        <input type="radio" id="radio2" name="radio"/>
                        <label class="radio-view" for="radio2"></label>
                        Radio Value 2
                    </span>

                    <span class="radio">
                        <input type="radio" id="radio1" name="radio" disabled="disabled"/>
                        <label class="radio-view" for="radio1"></label>
                        Radio Value (Disabled)
                    </span>
                </div>

                <div class="control-group">
                    <label for="">Checkbox Field</label>
                    <span class="checkbox">
                        <input type="checkbox" id="checkbox1" name="checkbox[]"/>
                        <label class="checkbox-view" for="checkbox1"></label>
                        Checkbox Value 1
                    </span>

                    <span class="checkbox">
                        <input type="checkbox" id="checkbox2" name="checkbox[]"/>
                        <label class="checkbox-view" for="checkbox2"></label>
                        Checkbox Value 2
                    </span>

                    <span class="checkbox">
                        <input type="checkbox" id="checkbox2" name="checkbox[]" disabled="disabled"/>
                        <label class="checkbox-view" for="checkbox2"></label>
                        Checkbox Value (Disabled)
                    </span>
                </div>
            </div>
        </div>

        <label class="styleguide-label">Dropdown</label>
        <div class="styleguide-wrapper">
            <div style="display: inline-block">
                <button class="dropdown-btn dropdown-toggle">
                    Top Left
                    <i class="icon arrow-down-icon"></i>
                </button>
                <div class="dropdown-list top-left">
                    <div class="dropdown-container">
                        <label>Dropdown Label</label>
                        <ul>
                            <li>
                                <a href="#">Dropdown Item 1</a>
                            </li>
                            <li>
                                <a href="#">Dropdown Item 2</a>
                            </li>
                            <li>
                                <a href="#">Dropdown Item 3</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>


            <div style="display: inline-block">
                <button class="dropdown-btn dropdown-toggle">
                    Top Right
                    <i class="icon arrow-down-icon"></i>
                </button>
                <div class="dropdown-list top-right">
                    <div class="dropdown-container">
                        <label>Dropdown Label</label>
                        <ul>
                            <li>
                                <a href="#">Dropdown Item 1</a>
                            </li>
                            <li>
                                <a href="#">Dropdown Item 2</a>
                            </li>
                            <li>
                                <a href="#">Dropdown Item 3</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>


            <div style="display: inline-block">
                <button class="dropdown-btn dropdown-toggle">
                    Bottom Left
                    <i class="icon arrow-down-icon"></i>
                </button>
                <div class="dropdown-list bottom-left">
                    <div class="dropdown-container">
                        <label>Dropdown Label</label>
                        <ul>
                            <li>
                                <a href="#">Dropdown Item 1</a>
                            </li>
                            <li>
                                <a href="#">Dropdown Item 2</a>
                            </li>
                            <li>
                                <a href="#">Dropdown Item 3</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>


            <div style="display: inline-block">
                <button class="dropdown-btn dropdown-toggle">
                    Bottom Right
                    <i class="icon arrow-down-icon"></i>
                </button>
                <div class="dropdown-list bottom-right">
                    <div class="dropdown-container">
                        <label>Dropdown Label</label>
                        <ul>
                            <li>
                                <a href="#">Dropdown Item 1</a>
                            </li>
                            <li>
                                <a href="#">Dropdown Item 2</a>
                            </li>
                            <li>
                                <a href="#">Dropdown Item 3</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <label class="styleguide-label">Pagination</label>
        <div class="styleguide-wrapper">
            <div class="pagination">
                <a class="page-item previous">
                    <i class="icon angle-right-icon"></i>
                </a>
                <a class="page-item active">1</a>
                <a class="page-item" href="#status/6/page/2">2</a>
                <a class="page-item" href="#status/6/page/3">3</a>
                <a class="page-item" href="#status/6/page/4">4</a>
                <a class="page-item" href="#status/6/page/5">5</a>
                <a class="page-item" style="text-decoration: none">…</a>
                <a class="page-item" href="#status/6/page/1899">1899</a>
                <a href="#status/6/page/2" class="page-item next">
                    <i class="icon angle-left-icon"></i>
                </a>
            </div>
        </div>

        <label class="styleguide-label">Table</label>
        <div class="styleguide-wrapper">
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>Cloumn Header 1</th>
                            <th>Cloumn Header 2</th>
                            <th>Cloumn Header 3</th>
                            <th>Cloumn Header 4</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>Cloumn 1 Row 1 Value</td>
                            <td>Cloumn 2 Row 1 Value</td>
                            <td>Cloumn 3 Row 1 Value</td>
                            <td>Cloumn 4 Row 1 Value</td>
                        </tr>

                        <tr>
                            <td>Cloumn 1 Row 2 Value</td>
                            <td>Cloumn 2 Row 2 Value</td>
                            <td>Cloumn 3 Row 2 Value</td>
                            <td>Cloumn 4 Row 2 Value</td>
                        </tr>

                        <tr>
                            <td>Cloumn 1 Row 3 Value</td>
                            <td>Cloumn 2 Row 3 Value</td>
                            <td>Cloumn 3 Row 3 Value</td>
                            <td>Cloumn 4 Row 3 Value</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>

    <script type="text/javascript" src="{{ asset('vendor/webkul/ui/assets/js/ui.js') }}"></script>
</html>