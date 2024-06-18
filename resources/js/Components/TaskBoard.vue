<template>
    <div class="flex flex-column gap-2" id="board">
        <ul class="board-column"
            v-for="column in columns"
            :key="column.id"
        >
            <h3>{{ column.name }}</h3>
            <li class="board-item"
                v-for="task in column.tasks"
                :key="task.id"
            >
                <p style="border: 1px solid black">{{ task.name }}</p>
            </li>
        </ul>
    </div>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
import Sortable from "sortablejs/modular/sortable.complete.esm.js";

export default {
    name: "TaskBoard",
    components: {AuthenticatedLayout, Head},
    props: {
        project: Object
    },
    data() {
        return {
            columns: this.project.columns,
            // columns: [
            //     {
            //         id: 1,
            //         name: "column_1",
            //         items: [
            //             {
            //                 id: 1,
            //                 name: "item1",
            //                 color: "blue"
            //             },
            //             {
            //                 id: 2,
            //                 name: "item2",
            //                 color: "red"
            //             },
            //             {
            //                 id: 3,
            //                 name: "item3",
            //                 color: "purple"
            //             }
            //         ]
            //     },
            //     {
            //         id: 2,
            //         name: "column_2",
            //         items: [
            //             {
            //                 id: 4,
            //                 name: "item4",
            //                 color: "blue"
            //             },
            //             {
            //                 id: 5,
            //                 name: "item5",
            //                 color: "red"
            //             },
            //             {
            //                 id: 6,
            //                 name: "item6",
            //                 color: "purple"
            //             }
            //         ]
            //     },
            //     {
            //         id: 3,
            //         name: "column_3",
            //         items: [
            //             {
            //                 id: 7,
            //                 name: "item7",
            //                 color: "blue"
            //             },
            //             {
            //                 id: 8,
            //                 name: "item8",
            //                 color: "red"
            //             },
            //             {
            //                 id: 9,
            //                 name: "item9",
            //                 color: "purple"
            //             }
            //         ]
            //     },
            // ],
            boardConfig: {
                group: "name",  // or { name: "...", pull: [true, false, 'clone', array], put: [true, false, array] }
                sort: true,  // sorting inside list
                delay: 0, // time in milliseconds to define when the sorting should start
                delayOnTouchOnly: false, // only delay if user is using touch
                touchStartThreshold: 0, // px, how many pixels the point should move before cancelling a delayed drag event
                disabled: false, // Disables the sortable if set to true.
                store: null,  // @see Store
                animation: 150,  // ms, animation speed moving items when sorting, `0` — without animation
                easing: "cubic-bezier(1, 0, 0, 1)", // Easing for animation. Defaults to null. See https://easings.net/ for examples.
                handle: ".my-handle",  // Drag handle selector within list items
                filter: ".ignore-elements",  // Selectors that do not lead to dragging (String or Function)
                preventOnFilter: true, // Call `event.preventDefault()` when triggered `filter`
                draggable: ".item",  // Specifies which items inside the element should be draggable

                dataIdAttr: 'data-id', // HTML attribute that is used by the `toArray()` method

                ghostClass: "sortable-ghost",  // Class name for the drop placeholder
                chosenClass: "sortable-chosen",  // Class name for the chosen item
                dragClass: "sortable-drag",  // Class name for the dragging item

                swapThreshold: 1, // Threshold of the swap zone
                invertSwap: false, // Will always use inverted swap zone if set to true
                invertedSwapThreshold: 1, // Threshold of the inverted swap zone (will be set to swapThreshold value by default)
                direction: 'horizontal', // Direction of Sortable (will be detected automatically if not given)

                forceFallback: false,  // ignore the HTML5 DnD behaviour and force the fallback to kick in

                fallbackClass: "sortable-fallback",  // Class name for the cloned DOM Element when using forceFallback
                fallbackOnBody: false,  // Appends the cloned DOM Element into the Document's Body
                fallbackTolerance: 0, // Specify in pixels how far the mouse should move before it's considered as a drag.

                dragoverBubble: false,
                removeCloneOnHide: true, // Remove the clone element when it is not showing, rather than just hiding it
                emptyInsertThreshold: 5, // px, distance mouse must be from empty sortable to insert drag element into it


                setData: function (/** DataTransfer */dataTransfer, /** HTMLElement*/dragEl) {
                    dataTransfer.setData('Text', dragEl.textContent); // `dataTransfer` object of HTML5 DragEvent
                },

                // Element is chosen
                onChoose: function (/**Event*/evt) {
                    evt.oldIndex;  // element index within parent
                },

                // Element is unchosen
                onUnchoose: function(/**Event*/evt) {
                    // same properties as onEnd
                },

                // Element dragging started
                onStart: function (/**Event*/evt) {
                    evt.oldIndex;  // element index within parent
                },

                // Element dragging ended
                onEnd: function (/**Event*/evt) {
                    var itemEl = evt.item;  // dragged HTMLElement
                    evt.to;    // target list
                    evt.from;  // previous list
                    evt.oldIndex;  // element's old index within old parent
                    evt.newIndex;  // element's new index within new parent
                    evt.oldDraggableIndex; // element's old index within old parent, only counting draggable elements
                    evt.newDraggableIndex; // element's new index within new parent, only counting draggable elements
                    evt.clone // the clone element
                    evt.pullMode;  // when item is in another sortable: `"clone"` if cloning, `true` if moving
                },

                // Element is dropped into the list from another list
                onAdd: function (/**Event*/evt) {
                    // same properties as onEnd
                },

                // Changed sorting within list
                onUpdate: function (/**Event*/evt) {
                    // same properties as onEnd
                },

                // Called by any change to the list (add / update / remove)
                onSort: function (/**Event*/evt) {
                    // same properties as onEnd
                },

                // Element is removed from the list into another list
                onRemove: function (/**Event*/evt) {
                    // same properties as onEnd
                },

                // Attempt to drag a filtered element
                onFilter: function (/**Event*/evt) {
                    var itemEl = evt.item;  // HTMLElement receiving the `mousedown|tapstart` event.
                },

                // Event when you move an item in the list or between lists
                onMove: function (/**Event*/evt, /**Event*/originalEvent) {
                    // Example: https://jsbin.com/nawahef/edit?js,output
                    evt.dragged; // dragged HTMLElement
                    evt.draggedRect; // DOMRect {left, top, right, bottom}
                    evt.related; // HTMLElement on which have guided
                    evt.relatedRect; // DOMRect
                    evt.willInsertAfter; // Boolean that is true if Sortable will insert drag element after target by default
                    originalEvent.clientY; // mouse position
                    // return false; — for cancel
                    // return -1; — insert before target
                    // return 1; — insert after target
                    // return true; — keep default insertion point based on the direction
                    // return void; — keep default insertion point based on the direction
                },

                // Called when creating a clone of element
                onClone: function (/**Event*/evt) {
                    var origEl = evt.item;
                    var cloneEl = evt.clone;
                },

                // Called when dragging element changes position
                onChange: function(/**Event*/evt) {
                    evt.newIndex // most likely why this event is used is to get the dragging element's current index
                    // same properties as onEnd
                }
            }
        }
    },
    mounted(){
        let columns = document.querySelectorAll('.board-column');
        columns.forEach((column) => {
            new Sortable(column, {
                group: 'shared', // set both lists to same group
                animation: 150
            });

        })
    }
}
</script>

<style scoped>

</style>