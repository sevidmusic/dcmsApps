<div style="background: #1f70ff;color: #040e21; border: 3px solid #ffffff;border-radius: 20px;padding:20px;"
     id="AjaxRouter">
    <div style="max-height: 468px;overflow:auto;" id="AjaxOutput"></div>
    <button class="dcms-button" onclick="AjaxRouterRequest()">Test Default Request</button>
    <button class="dcms-button" onclick="AjaxRouterRequest('AjaxRouterTester','getRequest','AjaxOutput','GET')">Test GET
        Request
    </button>
    <button class="dcms-button" onclick="AjaxRouterRequest('AjaxRouterTester','postRequest','AjaxOutput','POST')">Test
        POST Request
    </button>
    <button class="dcms-button"
            onclick="AjaxRouterRequest('AjaxRouterTester','getRequestParams','AjaxOutput','GET',undefined,'param1=value1&param2=value2')">
        Test GET Request with additional params
    </button>
    <!-- HINT: When using this function, pass undefined (no quotes) to any parameters that should assume default values. -->
    <button class="dcms-button"
            onclick="AjaxRouterRequest('AjaxRouterTester','postRequestParams','AjaxOutput','POST',undefined,'param1=value1&param2=value2')">
        Test POSTRequest with additional params
    </button>
    <button class="dcms-button"
            onclick="AjaxRouterRequest('AjaxRouterTester','altDirRequestGET','AjaxOutput','GET',undefined,'param1=value1&param2=value2','altDir')">
        Test alternative ajax dir using GET
    </button>
    <button class="dcms-button"
            onclick="AjaxRouterRequest('AjaxRouterTester','altDirRequestPOST','AjaxOutput','POST',undefined,'param1=value1&param2=value2','altDir')">
        Test alternative ajax dir using POST
    </button>
    <button class="dcms-button"
            onclick="AjaxRouterRequest('AjaxRouterTester','getRequest','AjaxOutput','GET',undefined,undefined,undefined,'AMShowMessages')">
        Test callFunction GET
    </button>
    <button class="dcms-button"
            onclick="AjaxRouterRequest('AjaxRouterTester','postRequest','AjaxOutput','POST',undefined,undefined,undefined,'AMHideMessages')">
        Test callFunction POST
    </button>
</div>
