function showModal(elementID)
{
    document.getElementById(elementID).style.display = 'block';
    document.getElementById("background").style.display = 'block';
}
function hideModal(elementID)
{
    document.getElementById(elementID).style.display = 'none';
    document.getElementById("background").style.display = 'none';
}

var sidebar = 0;
function showsidebar(ID) {
    if (sidebar == 0) {
        document.getElementById(ID).style.display = "block";
        sidebar = 1;
    }
    else if (sidebar == 1) {
        document.getElementById(ID).style.display = "none";
        sidebar = 0;
    }
}


////for auto add comma please indicate this to your textbox 
/////onkeyup = "javascript:this.value=Comma(this.value);"
        function Comma(Num) {
            Num += '';
            Num = Num.replace(/,/g, '');

            x = Num.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1))
                x1 = x1.replace(rgx, '$1' + ',' + '$2');

            return x1 + x2;
        }
        
        
 
  //input this to the textbox (onkeypress="return numericOnly(this);") only numbers allowed
    function numericOnly(elementRef) {
        var keyCodeEntered = (event.which) ? event.which : 
        (window.event.keyCode) ? window.event.keyCode : -1;
        if ((keyCodeEntered > 47) && (keyCodeEntered < 58)) {

            return true;
        }
       
      
        return false;
    }
    // -->
  //input this to the textbox (onkeypress="return DecinumericOnly(this);") only 1 dot and numbers allowed
    function DecinumericOnly(elementRef) {
        var keyCodeEntered = (event.which) ? event.which : 
        (window.event.keyCode) ? window.event.keyCode : -1;
        if ((keyCodeEntered > 47) && (keyCodeEntered < 58)) {

            return true;
        }
            // '.' decimal point...
        else if (keyCodeEntered == 46) {
            // Allow only 1 decimal point ('.')...
            if ((elementRef.value) && (elementRef.value.indexOf('.') >= 0))
                return false;
            else
                return true;
        }

        return false;
    }
    // -->



//No Quotation
    //input this to the textbox (onkeypress="return noquotation(event);") to not allowed single quotation
    function noquotation(evt) {

        var keyCode = evt.which ? evt.which : evt.keyCode;
        
        return (keyCode != "'".charCodeAt());
        
    }
// -->

//Avoid Space
    function AvoidSpace(event) {
        var keyCode = event ? event.which : window.keyCode;
        if (keyCode == 32) return false;
    }

//No Quotation and Space
    function noquotation_Space(evt) {
        var keyCode = evt.which ? evt.which : evt.keyCode;
        if (keyCode == 32) return false;
        return (keyCode != "'".charCodeAt());

    }

        document.onkeydown = function (e) {
            if (event.keyCode == 123) {
                return false;
            }
            if (e.ctrlKey && e.shiftKey && (e.keyCode == 'I'.charCodeAt(0) || e.keyCode == 'i'.charCodeAt(0))) {
                return false;
            }
            if (e.ctrlKey && e.shiftKey && (e.keyCode == 'C'.charCodeAt(0) || e.keyCode == 'c'.charCodeAt(0))) {
                return false;
            }
            if (e.ctrlKey && e.shiftKey && (e.keyCode == 'J'.charCodeAt(0) || e.keyCode == 'j'.charCodeAt(0))) {
                return false;
            }
            if (e.ctrlKey && (e.keyCode == 'U'.charCodeAt(0) || e.keyCode == 'u'.charCodeAt(0))) {
                return false;
            }
            if (e.ctrlKey && (e.keyCode == 'S'.charCodeAt(0) || e.keyCode == 's'.charCodeAt(0))) {
                return false;
            }
          

        }



