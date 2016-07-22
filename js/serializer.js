/**
 * Created by Andy on 7/22/2016.
 */

var Serializer = function() {
    this.objects = {};
    this.unserializedObjects = {};

    var serializer = this;

    this.reset = function(){
        this.objects = {};
        this.unserializedObjects = {};
        this.clear();
    };

    this.save = function(name) {
        localStorage.setItem(name, JSON.stringify(this.objects));
    };

    this.load = function(name) {
        serializer.objects = $.parseJSON(localStorage.getItem(name));
    };

    this.clear = function(name) {
        localStorage.removeItem(name);
    }

    this.serialize = function(object) {
        if(object == null) {
            console.log("No object to serialize.  Returning null");
            return null;
        }
        console.debug("Serializing " + object.id());
        var objectId = object.id();
        if(typeof this.objects[objectId] == 'undefined') {
            var objSerialized = {};
            var type = object.constructor.name;
            console.debug("[" + objectId + "]Object is a " + type);
            serializer.objects[objectId] = {
                type: type,
                data: objSerialized
            };
            object.simpleFields().forEach(function(name) {
                console.debug("[" + objectId + "]Serializing simple field " + name);
                objSerialized[name] = object[name];
            });
            object.objectFields().forEach(function(name) {
                console.debug("[" + objectId + "]Serializing object field " + name);
                objSerialized[name] = serializer.serialize(object[name]);
            });
            object.objectLists().forEach(function(name) {
                console.debug("[" + objectId + "]Serializing object list " + name);
                objSerialized[name] = object[name].map(function(listObject) {
                    return serializer.serialize(listObject);
                });
            });
        } else {
            console.debug("[" + objectId + "]Object already exists.");
        }

        console.debug("[" + objectId + "]Returning objectId");
        return objectId;
    };

    this.unserialize = function(name) {
        console.debug("Unserializing " + name);
        var dispName = name;
        if(typeof serializer.unserializedObjects[name] == 'undefined') {
            if(!serializer.objects[name]) {
                console.debug("[" + dispName + "]Object does not exist in serializer. Returning null")
                return null;
            }
            var type = serializer.objects[name].type;
            var data = serializer.objects[name].data;
            console.debug("[" + dispName + "]Object is a " + type);
            var obj = new window[type]();
            serializer.unserializedObjects[name] = obj;

            obj.simpleFields().forEach(function(name){
                console.debug("[" + dispName + "]Unserializing simple field " + name + " with value " + data[name]);
                obj[name] = data[name];
            });
            obj.objectFields().forEach(function(name) {
                console.debug("[" + dispName + "]Unserializing object field " + name);
                obj[name] = serializer.unserialize(data[name]);
            });
            obj.objectLists().forEach(function(listName){
                console.debug("[" + dispName + "]Unserializing object list " + listName);
                obj[listName] = data[listName].map(function(objName){
                    return serializer.unserialize(objName);
                })
            });
        } else {
            console.log("[" + dispName + "]Already exists");
        }

        console.debug("[" + dispName + "]Returning object " + name);
        return serializer.unserializedObjects[name];
    };
};
