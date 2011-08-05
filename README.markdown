#Pro Menus plugin for Croogo

Pro Menus enhances Croogo's menu system in the following ways:

 - Adds a textarea to the add/edit links admin pages where you can enter PHP code that determines if the link is selected
 - Provides a way to specify which level of links you want displayed and the number of sub-levels required
 - Includes a helper method to display a breadcrumb
 - Adds a 'selected_trail' class for parent links of the selected link
 - Optionally adds 'first' and 'last' classes

##Examples & Usage

###PHP code for determining selected state * **
Click on the 'Pro' tab of the add/edit link page and enter PHP code. You have access to params via $this->params. Example: entering `return $this->params['plugin'] == 'gallery';` will keep the link selected for every controller/action combo of the gallery plugin

\* SECURITY WARNING: This uses a simple `eval()` of whatever is entered into the textarea. Some kind of sandbox has to be built. Suggestions welcome.

\** Only works if you are using the Pro Menus Helper.

###Pro Menus Helper
The Pro Menus Helper provides a replacement for the Layout Helper's `menu` method and a way to display a breadcrumb.

####Displaying a menu
Use the Pro Menus Helper's `menu` method instead of `$this->Layout->menu()`:

 - `$this->ProMenus->menu('main', array('sub_levels' => 2))` will display the first level, and two sub levels
 - `$this->ProMenus->menu('main', array('for_level' => 1, 'sub_levels' => 1))` will display the child links of whichever first level link is selected or is part of the selected trail, and the next sub level

#####Options

<table>
    <tr>
        <th>Option</th>
        <th>Values</th>
        <th>Default</th>
        <th>Description</th>
    </tr>
    <tr>
        <td>for_level</td>
        <td>int</td>
        <td>0</td>
        <td>specifies the level of links whose children are to be displayed (0 will display the first level of links)</td>
    </tr>
    <tr>
        <td>sub_levels</td>
        <td>int/null</td>
        <td>null</td>
        <td>specifies the number of generations of children (of the level specified in `for_level`) to be included (null will display all further generations, 0 will display none)</td>
    </tr>
    <tr>
        <td>selected</td>
        <td>str</td>
        <td>'selected'</td>
        <td>specify the class to use for marking selected links</td>
    </tr>
    <tr>
        <td>selected_trail</td>
        <td>str</td>
        <td>'selected_trail'</td>
        <td>specify the class to use for marking parents of selected links</td>
    </tr>
    <tr>
        <td>selected_for_li</td>
        <td>bool</td>
        <td>false</td>
        <td>indicates if you want the `selected` and `selected_trail` classes to also be applied to the parent element of the link (the `li` tag)</td>
    </tr>
    <tr>
        <td>first</td>
        <td>str/false</td>
        <td>false</td>
        <td>specifies the class / turns off classes indicating first links</td>
    </tr>
    <tr>
        <td>last</td>
        <td>str/false</td>
        <td>'last'</td>
        <td>specifies the class / turns off classes indicating last links</td>
    </tr>
    <tr>
        <td>first_last_for_li</td>
        <td>bool</td>
        <td>false</td>
        <td>indicates if you want the `first` and/or `last` classes to also be applied to the parent element of the link (the `li` tag)</td>
    </tr>
</table>
 
####Displaying a breadcrumb:

 - `$this->ProMenus->breadcrumb('main', array('separator' => ' &rarr; ')` displays the breadcrumb for the 'main' menu
 
#####Options

<table>
    <tr>
        <th>Option</th>
        <th>Values</th>
        <th>Default</th>
        <th>Description</th>
    </tr>
    <tr>
        <td>separator</td>
        <td>str</td>
        <td>' &rarr; '</td>
        <td>specifies the separator to be used between crumbs</td>
    </tr>
    <tr>
        <td>tag</td>
        <td>str</td>
        <td>'div'</td>
        <td>specifies which tag contains the links and separators</td>
    </tr>
    <tr>
        <td>tagAttributes</td>
        <td>array</td>
        <td>array('class' => 'breadcrumb')</td>
        <td>specifies attrubutes for the container tag</td>
    </tr>
</table>


##Installation & activation
 - Rename the downloaded folder to 'pro_menus' and place it in your Croogo installation's app/plugins folder.
 - Goto Extensions->Plugins in the admin section.
 - Click 'Activate' next to the 'Pro Menus' entry.

##License ([MIT](http://www.opensource.org/licenses/mit-license.php))

Copyright (c) 2011 Palaniappan Chellappan

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.