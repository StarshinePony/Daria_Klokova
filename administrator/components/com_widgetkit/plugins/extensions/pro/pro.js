"use strict";(function(o){o.events.on("openItemPicker",function(t,r){if(r.module==="widgetkit"){t.stopPropagation();var e=t.origin.$modal({render:function(d){return d("div",{attrs:{"uk-overflow-auto":"expand: true"},on:{resize:function(n){n.target.firstElementChild.style.height=n.target.style.maxHeight}}},[d("iframe",{attrs:{src:o.url(window.widgetkit.config.route,{p:"/picker",tmpl:"component"})},on:{load:function(n){n.target.contentDocument.body.style.padding="30px"}},style:"width: 100%; height: 100%"})])}});return window.selectWidget=function(i){e.resolve(i.id),delete window.selectWidget},e.show({container:!0})}},5),o.events.on("resolveItemTitle",function(t,r){if(t.origin.field.module==="widgetkit"){t.stopPropagation();var e=window.widgetkit;return t.origin.$http.get(e.config.route,{params:{p:"/content/"+r.id},headers:{"X-XSRF-TOKEN":e.config.csrf}}).then(function(i){return i.body.name})}},5)})(window.Vue);
