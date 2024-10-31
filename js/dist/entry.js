jQuery(function($) {(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;

var _RulesTable = _interopRequireDefault(require("./RulesTable"));

var _RecordsTable = _interopRequireDefault(require("./RecordsTable"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Admin = function Admin() {
  _classCallCheck(this, Admin);

  this.rulesTable = new _RulesTable["default"]($("#rules table"));
  this.recordsTable = new _RecordsTable["default"]($("#records table"));
  $("#rest-cache-tabs").tabs();
};

exports["default"] = Admin;
window.RESTCache = {
  admin: new Admin()
};

},{"./RecordsTable":2,"./RulesTable":3}],2:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;

var _Table2 = _interopRequireDefault(require("./Table"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

var RecordsTable = /*#__PURE__*/function (_Table) {
  _inherits(RecordsTable, _Table);

  var _super = _createSuper(RecordsTable);

  function RecordsTable(element) {
    var _this;

    _classCallCheck(this, RecordsTable);

    _this = _super.call(this, element);
    $("#rest-cache-admin #refresh-table").on("click", function (event) {
      return _this.onRefreshTable(event);
    });
    $("#rest-cache-admin #clear-cache").on("click", function (event) {
      return _this.onClearCache(event);
    });
    return _this;
  }

  _createClass(RecordsTable, [{
    key: "onRefreshTable",
    value: function onRefreshTable(event) {
      $("#rest-cache-admin #records .dataTable").DataTable().ajax.reload();
    }
  }, {
    key: "onClearCache",
    value: function onClearCache(event) {
      var self = this;
      $.ajax(this.url, {
        method: "DELETE",
        success: function success(response, status, xhr) {
          self.onActionComplete(response);
        }
      });
    }
  }, {
    key: "onActionComplete",
    value: function onActionComplete(event) {
      this.$element.DataTable().ajax.reload();
    }
  }]);

  return RecordsTable;
}(_Table2["default"]);

exports["default"] = RecordsTable;

},{"./Table":4}],3:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;

var _Table2 = _interopRequireDefault(require("./Table"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _get(target, property, receiver) { if (typeof Reflect !== "undefined" && Reflect.get) { _get = Reflect.get; } else { _get = function _get(target, property, receiver) { var base = _superPropBase(target, property); if (!base) return; var desc = Object.getOwnPropertyDescriptor(base, property); if (desc.get) { return desc.get.call(receiver); } return desc.value; }; } return _get(target, property, receiver || target); }

function _superPropBase(object, property) { while (!Object.prototype.hasOwnProperty.call(object, property)) { object = _getPrototypeOf(object); if (object === null) break; } return object; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

var RulesTable = /*#__PURE__*/function (_Table) {
  _inherits(RulesTable, _Table);

  var _super = _createSuper(RulesTable);

  function RulesTable(element) {
    var _this;

    _classCallCheck(this, RulesTable);

    _this = _super.call(this, element);

    _this.$element.on("click", "[data-action='cancel']", function (event) {
      return _this.onCancel(event);
    });

    $("button#add-rule").on("click", function (event) {
      return _this.onAddRule(event);
    });
    return _this;
  }

  _createClass(RulesTable, [{
    key: "getControlFromField",
    value: function getControlFromField(field) {
      switch (field) {
        case "regex":
          var $input = _get(_getPrototypeOf(RulesTable.prototype), "getControlFromField", this).call(this, field);

          $input.attr("type", "checkbox");
          return $input;
          break;

        case "behaviour":
          var $select = $("<select name='behaviour'>\
					<option value='exclude'>Exclude</option>\
					<option value='include'>Include</option>\
				</select>");
          return $select;
          break;

        case "priority":
          var $input = _get(_getPrototypeOf(RulesTable.prototype), "getControlFromField", this).call(this, field);

          $input.attr("type", "number");
          return $input;
          break;
      }

      return _get(_getPrototypeOf(RulesTable.prototype), "getControlFromField", this).call(this, field);
    }
  }, {
    key: "onCancel",
    value: function onCancel(event) {
      this.$element.DataTable().ajax.reload();
    }
  }, {
    key: "onAddRule",
    value: function onAddRule(event) {
      var self = this;
      $.ajax(this.url, {
        method: "POST",
        success: function success(response, status, xhr) {
          self.$element.DataTable().ajax.reload();
        }
      });
    }
  }]);

  return RulesTable;
}(_Table2["default"]);

exports["default"] = RulesTable;

},{"./Table":4}],4:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;

var _dataTable = _interopRequireDefault(require("@perry-rylance/data-table"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

var Table = /*#__PURE__*/function (_DataTable) {
  _inherits(Table, _DataTable);

  var _super = _createSuper(Table);

  function Table(element) {
    var _this;

    _classCallCheck(this, Table);

    _this = _super.call(this, element);
    _this.$element = $(element);
    _this.url = _this.$element.attr("data-route");

    _this.$element.on("click", "[data-action='edit']", function (event) {
      return _this.onEdit(event);
    });

    _this.$element.on("click", "[data-action='update']", function (event) {
      return _this.onUpdate(event);
    });

    _this.$element.on("click", "[data-action='delete']", function (event) {
      return _this.onDelete(event);
    });

    return _this;
  }

  _createClass(Table, [{
    key: "getIDFromEvent",
    value: function getIDFromEvent(event) {
      var $tr = $(event.currentTarget).closest("tr");
      var id = $tr.attr("data-id");
      return id;
    }
  }, {
    key: "getControlFromField",
    value: function getControlFromField(field) {
      var $input = $("<input/>");
      $input.attr("name", field);
      return $input;
    }
  }, {
    key: "setItemEditable",
    value: function setItemEditable(id) {
      var self = this;
      var $tr = this.$element.find("tr[data-id='" + id + "']");
      $tr.children("td").each(function (index, td) {
        var field = $(td).attr("data-field");

        switch (field) {
          case "id":
          case "actions":
            return true;
            break;
        }

        var $input = self.getControlFromField(field);

        switch ($input.attr("type")) {
          case "checkbox":
            $input.prop("checked", $(td).text() == 1);
            break;

          default:
            $input.val($(td).text());
            break;
        }

        $(td).empty();
        $(td).append($input);
      });
      $tr.addClass("rest-cache-editing");
    }
  }, {
    key: "onEdit",
    value: function onEdit(event) {
      var id = this.getIDFromEvent(event);
      this.setItemEditable(id);
    }
  }, {
    key: "onUpdate",
    value: function onUpdate(event) {
      var self = this;
      var id = this.getIDFromEvent(event);
      var data = {};
      var $tr = $(event.currentTarget).closest("tr");
      $tr.find(":input").each(function (index, el) {
        var name = $(el).attr("name");
        if (!name) return;

        switch ($(el).attr("type")) {
          case "checkbox":
          case "radio":
            data[name] = $(el).prop("checked") ? 1 : 0;
            break;

          default:
            data[name] = $(el).val();
            break;
        }
      });
      $.ajax(this.url + "/" + id, {
        method: "PUT",
        data: data,
        success: function success(response, status, xhr) {
          self.onActionComplete(response);
        },
        error: function error(xhr, status, _error) {
          if (xhr.status == 422) {
            var json = JSON.parse(xhr.responseText);
            self.onError(xhr.status, json);
          }
        }
      });
    }
  }, {
    key: "onDelete",
    value: function onDelete(event) {
      var self = this;
      var id = this.getIDFromEvent(event);
      $.ajax(this.url + "/" + id, {
        method: "DELETE",
        success: function success(response, status, xhr) {
          self.onActionComplete(response);
        }
      });
    }
  }, {
    key: "onActionComplete",
    value: function onActionComplete(event) {
      this.$element.DataTable().ajax.reload();
    }
  }, {
    key: "onError",
    value: function onError(code, json) {
      for (var key in json.errors) {
        alert(json.errors[key]);
      }
    }
  }]);

  return Table;
}(_dataTable["default"]);

exports["default"] = Table;

},{"@perry-rylance/data-table":6}],5:[function(require,module,exports){
"use strict";

require('./Admin');

},{"./Admin":1}],6:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var DataTable = /*#__PURE__*/function () {
  function DataTable(element) {
    _classCallCheck(this, DataTable);

    if ($.fn.dataTable.ext) $.fn.dataTable.ext.errMode = "throw";
    this.$element = $(element);
    var options = this.getDataTableOptions();
    this.$element.DataTable(options);
  }

  _createClass(DataTable, [{
    key: "getDataTableOptions",
    value: function getDataTableOptions() {
      var fields = this.getColumnFields();
      var columns = [];
      fields.forEach(function (field) {
        columns.push({
          "data": field
        });
      });
      return {
        "ajax": this.$element.attr("data-route"),
        "processing": true,
        "serverSide": true,
        "columns": columns,
        "createdRow": function createdRow(row, data, index) {
          if (!("id" in data)) console.warn("No ID in row data");else $(row).attr("data-id", data.id);
          var index = 0;
          $(row).children("td").each(function (index, td) {
            $(td).attr("data-field", fields[index]);
            index++;
          });
        }
      };
    }
  }, {
    key: "getColumnFields",
    value: function getColumnFields() {
      var results = [];
      this.$element.find("th[data-column-field]").each(function () {
        results.push($(this).attr("data-column-field"));
      });
      return results;
    }
  }]);

  return DataTable;
}();

exports["default"] = DataTable;

DataTable.createInstance = function (element) {
  return new DataTable(element);
};

$(window).on("load", function (event) {
  $("table.perry-rylance-datatable").each(function (index, el) {
    if ($(el).attr("data-auto-initialize") == "false") return;
    el.dataTable = DataTable.createInstance(el);
  });
});

},{}]},{},[5])

});//# sourceMappingURL=entry.js.map
