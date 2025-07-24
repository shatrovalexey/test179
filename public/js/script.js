(win => {
    const {"document": doc, "location": loc,} = win;

    String.prototype.capitalize = function() {
        return this.charAt(0).toUpperCase() + this.substr(1);
    };

    doc.addEventListener("DOMContentLoaded", evt => {
        const form = doc.querySelector(".search-form");
        const queryInput = form.querySelector("*[name='q']");
        const table = form.querySelector(".search-table");
        const caption = table.querySelector("caption");
        const trTpl = form.querySelector("tbody > template");

        form.addEventListener("submit", evt => {
            evt.preventDefault();

            form.querySelectorAll("tbody > tr").forEach(tr => tr.remove());

            caption.textContent = "";

            const url = new URL(form.getAttribute("action"), loc.origin);
            const search = new URLSearchParams();

            search.set("q", queryInput.value);
            url.search = search;

            fetch(url)
                .then(data => data.json())
                .then(data => data.reverse())
                .then(data => data.forEach(item => {
                    caption.textContent = caption.getAttribute("title");

                    const tr = trTpl.content.cloneNode(true);

                    tr.querySelectorAll("*[data-content]")
                        .forEach(node => node.textContent = item[node.dataset.content]);

                    tr.querySelectorAll("*[data-attr]")
                        .forEach(node => {
                            const attrName = node.dataset.attr;

                            if (!attrName || !attrName.length) return;

                            const key = `attr${attrName.capitalize()}`;

                            if (key in node.dataset)
                                node.setAttribute(attrName, node.dataset[key].replace(/\{(.+?)\}/g, (_, p1) => item[p1]));
                        });

                    trTpl.after(tr);
                }))
                .catch(error => caption.textContent = error);

            return false;
        });
    });
})(window);