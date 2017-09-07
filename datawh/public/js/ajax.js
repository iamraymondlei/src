/**
 * ajax
 * Todo:
 *   1. 所有方法原样返回请求结果的JSON，不返回JSON.MallDB。
 *   2. 不使用alert，使用BootstrapDialog.show代替。
 *   3. 不使用XMLHttpRequest。
 */

var ajax = {async: false, successCallback: null, failCallback: null};


/**
 * ajax.GetShopList() returns a new object
 * based on the passed in mallId
 *
 * @param <String> mallId
 * @param <String> pageSize
 * @return <Object> object
 */
ajax.GetShopList = function(mallId,pageSize){
    var result = [];
    $.ajax({
        url: 'ws/GetShopList.php',
        type: 'GET',
        data:{
            format: "json",
            mallId: mallId,
            pageSize: pageSize
        },
        dataType : 'json',
        async: false,
        success: function(json) {
            result = json;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

/**
 * ajax.GetPromotionList() returns a new object
 * based on the passed in mallId
 *
 * @param <String> requestParams
 * @return <Object> object
 */
ajax.GetPromotionList = function(requestParams) {
    var result = false;
    $.ajax({
        url: '../../ws/GetPromotionList.php',
        type: 'GET',
        dataType : 'json',
        data: {
            format : "json",
            mallId: requestParams.mallId,
            shopId: requestParams.shopId,
            keyword: requestParams.keyword,
            promoTypeId: requestParams.promoTypeId,
            promoId: requestParams.promoId,
            comingEvent: requestParams.comingEvent,
            published: requestParams.published,
            page: requestParams.page,
            pageSize: requestParams.pageSize
        },
        async: false,
        success: function(json) {
            result = json;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

/**
 * ajax.SetPromotion() returns a new object
 * based on the action and data
 *
 * @param <String> action value:add or update
 * @param <JSON> data
 * @return <Object> object
 */
ajax.SetPromotion = function(action, data) {
    var result = false;
    $.ajax({
        url: '../../ws/SetPromotion.php',
        type: 'POST',
        dataType : 'json',
        data: {
            format: 'json',
            action: action,
            data: JSON.stringify(data)
        },
        async: false,
        success: function(json) {
            result = json;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

/**
 * ajax.GetPromotionDetail() returns a new object
 * based on the promotionId
 *
 * @param <String> promotionId
 * @return <Object> object
 */
ajax.GetPromotionDetail = function(promotionId){
    var result = [];
    $.ajax({
        url: '../../ws/GetPromotionDetail.php',
        data: {
            format: 'json',
            promotionId: promotionId
        },
        type: 'GET',
        dataType : 'json',
        async: false,
        success: function(json) {
            result = json;//.MallDB.PromotionList.Promotion;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

/**
 * ajax.SetPromotion() returns a new object
 * based on the action and data
 *
 * @param <String> action value:add or update
 * @param <JSON> data
 * @return <Object> object
 */
ajax.SetPromotion = function(action, data){
    var result = false;
    $.ajax({
        url: '../../ws/SetPromotion.php',
        type: 'POST',
        dataType : 'json',
        data: {
            format: 'json',
            action: action,
            data: JSON.stringify(data)
        },
        async: false,
        success: function(json) {
            result = json;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

/**
 * ajax.AddAuxItem() returns a new object
 * based on the passed in mallId
 *
 * @param <String> mallId
 * @param <String> pageSize
 * @return <Object> object
 */
ajax.AddAuxItem = function(requestParams) {
    var result = false;
    $.ajax({
        url: '../../ws/AddAuxItem.php',
        type: 'GET',
        dataType : 'json',
        data: {
            format : "json",
            shopId: requestParams.shopId,
            auxItemTypeId: requestParams.auxItemTypeId,
            //auxItemKey: requestParams.auxItemKey,
            itemData: JSON.stringify(requestParams.itemData)
        },
        async: false,
        success: function(json) {
            result = json.MallDB;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //$.alert("调用AddAuxItem服务失败，服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

/**
 * ajax.DelAuxItemByAuxItemId() returns a new object
 * based on the passed in mallId
 *
 * @param <String> mallId
 * @param <String> pageSize
 * @return <Object> object
 */
ajax.DelAuxItemByAuxItemId = function(requestParams) {
    var result = false;
    $.ajax({
        url: '../../ws/DelAuxItem.php',
        type: 'GET',
        dataType : 'json',
        data: {
            format : "json",
            auxItemId: requestParams
        },
        async: false,
        success: function(json) {
            result = json.MallDB;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

/**
 * ajax.CheckRemoteFileSize() returns a new object
 * based on the passed in mallId
 *
 * @param <String> mallId
 * @param <String> pageSize
 * @return <Object> object
 */
ajax.CheckRemoteFileSize = function(requestParams) {
    var result = false;
    $.ajax({
        url: '../../ws/CheckRemoteFileSize.php',
        type: 'GET',
        dataType : 'json',
        data: {
            format : "json",
            url: requestParams
        },
        async: false,
        success: function(json) {
            result = json.MallDB;
			if(result.ResultMessage != "Success"){
				result.Size="未知大小";
				alert("检测资料大小失败");
			}
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

/**
 * ajax.GetAppointmentItem() returns a new object
 * based on the passed in mallId
 *
 * @param <String> mallId
 * @param <String> pageSize
 * @return <Object> object
 */
ajax.GetAppointmentItem = function(requestParams) {
    var result = false;
    $.ajax({
        url: '../../ws/GetAppointmentItem.php',
        type: 'GET',
        dataType : 'json',
        data: {
            format : "json",
            enquiryTarget: requestParams.enquiryTarget,
            shopId: requestParams.shopId,
            sort: requestParams.sort,
            page: requestParams.page,
            pageSize: requestParams.pageSize
        },
        async: false,
        success: function(json) {
            result = json.MallDB;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

ajax.GetAnalyticsReportData = function(requestUrl){
    var result = false;
    $.ajax({
        url: '../../ws/proxy.php',
        type: 'GET',
        dataType : 'json',
        data: {
          requestUrl:requestUrl,
          format:'json'
        },
        async: false,
        success: function(json) {
            result = json;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}


/**
 * ajax.GetAuxItemDetail() returns a new object
 *
 * @param <Object> requestParams
 * @return <Object> object
 */
ajax.GetAuxItemDetail = function(requestParams) {
    var result = false;
    $.ajax({
        url: '../../ws/GetAuxItemDetail.php',
        type: 'GET',
        dataType : 'json',
        data: {
            format : "json",
            auxItemId: requestParams.auxItemId
        },
        async: false,
        success: function(json) {
            result = json.MallDB;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

/**
 * ajax.UpdateAuxItemByAuxItemId() returns a new object
 *
 * @param <Object> requestParams
 * @return <Object> object
 */
ajax.UpdateAuxItemByAuxItemId = function(requestParams) {
    var result = false;
    $.ajax({
        url: '../../ws/UpdateAuxItem.php',
        type: 'GET',
        dataType : 'json',
        data: {
            format : "json",
            auxItemId: requestParams["auxItemId"],
            auxItemData: requestParams["auxItemData"]
        },
        async: false,
        success: function(json) {
            result = json.MallDB;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}


/**
 * ajax.GetHotProduct() returns a new object
 * based on the passed in mallId
 *
 * @param <String> mallId
 * @param <String> pageSize
 * @return <Object> object
 */
ajax.GetHotProduct = function(requestParams) {
    var result = false;
    $.ajax({
        url: '../../ws/GetHotProduct.php',
        type: 'GET',
        dataType : 'json',
        data: {
            format : "json",
            shopId: requestParams.shopId,
            mallId: requestParams.mallId,
            auxItemTypeId: requestParams.auxItemTypeId,
            sort: requestParams.sort,
            date: requestParams.date,
            catId:requestParams.catId,
            version: requestParams.version,
            page: requestParams.page,
            pageSize: requestParams.pageSize
        },
        async: false,
        success: function(json) {
            result = json.MallDB;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

ajax.GetHotProductCat = function(requestParams) {
    var result = false;
    $.ajax({
        url: '../../ws/GetHotProductCat.php',
        type: 'GET',
        dataType : 'json',
        data: {
            format : "json",
            mallId: requestParams.mallId
        },
        async: false,
        success: function(json) {
            result = json.MallDB;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

ajax.GetProductCat = function(requestParams){
    var result = false;
    $.ajax({
        url: '../../ws/GetItemCatList.php',
        type: 'GET',
        dataType : 'json',
        data: {
            format : "json",
            shopId: requestParams.shopId,
            mallId: requestParams.mallId,
            catId: requestParams.itemCatId,
            itemTypeId: requestParams.itemTypeId
        },
        async: false,
        success: function(json) {
            result = json;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

ajax.SetItemCat = function(requestParams){
    var result = false;
    $.ajax({
        url: '../../ws/SetItemCat.php',
        type: 'GET',
        dataType : 'json',
        data: {
            format : "json",
            shopId: requestParams.shopId,
            itemCatId: requestParams.itemCatId,
            catLogoUrl: requestParams.catLogoUrl,
            sortOrder: requestParams.sortOrder,
            description: requestParams.description,
            itemCatName: requestParams.itemCatName,
            action: requestParams.action
        },
        async: false,
        success: function(json) {
            result = json;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

ajax.AddItemCat = function(requestParams){
    var result = false;
    $.ajax({
        url: '../../ws/SetItemCat.php',
        type: 'GET',
        dataType : 'json',
        data: {
            format : "json",
            shopId: requestParams.shopId,
            itemTypeId: requestParams.itemTypeId,
            catLogoUrl: requestParams.catLogoUrl,
            sortOrder: requestParams.sortOrder,
            action: requestParams.action,
            parentId: requestParams.parentId,
            itemCatName: requestParams.itemCatName
        },
        async: false,
        success: function(json) {
            result = json;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}


ajax.SetItem = function(action, postData){
    var result = false;
    $.ajax({
        url: '../../ws/SetItem.php',
        type: 'GET',
        dataType : 'json',
        data: {
            format : "json",
            data: JSON.stringify(postData),
            action: action
        },
        async: false,
        success: function(json) {
            result = json;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

ajax.GetItemList = function(requestParams){
    var result = false;
    $.ajax({
        url: '../../ws/GetItemList.php',
        type: 'GET',
        dataType : 'json',
        data: {
            format : "json",
            mallId:requestParams.mallId,
            shopId:requestParams.shopId,
            itemCatId:requestParams.itemCatId,
            itemTypeId:requestParams.itemTypeId,
            keyword:requestParams.keyword,
            page:requestParams.page,
            onShelf:requestParams.onShelf,
            pageSize:requestParams.pageSize,
            sort:requestParams.sort
        },
        async: false,
        success: function(json) {
            result = json;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

ajax.GetProductDetail = function(productId){
    var result = [];
    $.ajax({
        url: '../../ws/GetItemDetail.php',
        data: {
            format: 'json',
            itemId: productId
        },
        type: 'GET',
        dataType : 'json',
        async: false,
        success: function(json) {
            result = json;//.MallDB.PromotionList.Promotion;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

ajax.GetItemNvpTypeList = function(requestParams){
    var result = [];
    $.ajax({
        url: '../../ws/GetItemNvpTypeList.php',
        type: 'GET',
        data:{
            format: "json",
            shopId: requestParams.shopId,
            itemTypeId: requestParams.itemTypeId
        },
        dataType : 'json',
        async: false,
        success: function(json) {
            result = json;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

ajax.GetApiproxyData = function(requestParams){
    var result = false;
    $.ajax({
        url: '../../ws/proxy.php',
        type: 'GET',
        dataType : 'xml',
        data: {
          requestUrl:requestParams.requestUrl,
          name:requestParams.name,
          api:(requestParams.api)?requestParams.api:"",
          urlParams:requestParams.urlParams,
          postData:JSON.stringify(requestParams.itemData),
          format:'xml'
        },
        async: false,
        success: function(xml) {
            result = xml;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            $.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

ajax.GetShopDetail = function(mallId,shopId){
    var result = [];
    $.ajax({
        url: '../../ws/GetShopDetail.php',
        type: 'GET',
        data:{
            format: "json",
            mallId: mallId,
            shopId: shopId
        },
        dataType : 'json',
        async: false,
        success: function(json) {
            result = json;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

ajax.SetShop = function(action, postData){
    var result = false;
    $.ajax({
        url: '../../ws/SetShopDetail.php',
        type: 'GET',
        dataType : 'json',
        data: {
            format : "json",
            data: JSON.stringify(postData),
            action: action
        },
        async: false,
        success: function(json) {
            result = json;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

ajax.GetPromoType = function(requestParams) {
    var result = false;
    $.ajax({
        url: '../../ws/GetPromoTypeList.php',
        type: 'GET',
        dataType : 'json',
        data: {
            format : "json",
            mallId: requestParams.mallId,
            shopId: requestParams.shopId,
            promoTypeId: requestParams.promoTypeId
        },
        async: false,
        success: function(json) {
            result = json;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

ajax.SetPromoType = function(requestParams) {
    var result = false;
    $.ajax({
        url: '../../ws/SetPromoType.php',
        type: 'GET',
        dataType : 'json',
        data: {
            format : "json",
            promoTypeId: requestParams.promoTypeId,
            promoTypeImage: requestParams.promoTypeImage
        },
        async: false,
        success: function(json) {
            result = json;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

ajax.GetItemType = function(requestParams) {
    var result = false;
    $.ajax({
        url: '../../ws/GetItemType.php',
        type: 'GET',
        dataType : 'json',
        data: {
            format : "json",
            shopId: requestParams.shopId
        },
        async: false,
        success: function(json) {
            result = json;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

ajax.GetMallConfigNvp = function(params){
    var result = [];
    $.ajax({
        url: '../../ws/GetMallConfigNvp.php',
        type: 'GET',
        data:{
            format: "json",
            mallId: params.mallId,
            keyName: params.keyName
        },
        dataType : 'json',
        async: false,
        success: function(json) {
            result = json;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

ajax.GetNvpTypeList = function(params){
    var result = [];
    $.ajax({
        url: '../../ws/GetNvpTypeList.php',
        type: 'GET',
        data:{
            format: "json",
            type: params.type
        },
        dataType : 'json',
        async: false,
        success: function(json) {
            result = json;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

ajax.GetMallDetail = function(params){
    var result = [];
    $.ajax({
        url: '../../ws/GetMallDetail.php',
        type: 'GET',
        data:{
            format: "json",
            mallId: params.mallId
        },
        dataType : 'json',
        async: false,
        success: function(json) {
            result = json;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

ajax.GetRoleFeatureList = function(requestParams) {
    var result = false;
    $.ajax({
        url: '../../ws/GetRoleFeatureList.php',
        type: 'GET',
        dataType : 'json',
        data: {
            format : "json",
            roleFeatureId: requestParams.roleFeatureId
        },
        async: false,
        success: function(json) {
            result = json;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("服务访问异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}