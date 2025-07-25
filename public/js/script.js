(({"document": doc, "location": loc,}) => doc.addEventListener("DOMContentLoaded", () => {
    const form = doc.querySelector(".search-form");
    const table = form.querySelector(form.dataset.output);
    const trTpl = table.querySelector(table.dataset.output);
    const formUrl = new URL(form.getAttribute("action"), loc.origin);
    const formMsg = table.querySelector(table.dataset.msg);

    const getMsg = (...args) => args.filter(data => data && String(data).length).join(": ");
    const formGetMsg = (key, error) => getMsg(formMsg.dataset[key], error);
    const formSetMsg = (key, error) => formMsg.textContent = formGetMsg(key, error);

    form.addEventListener("submit", evt => {
        evt.preventDefault();

        table.querySelectorAll(table.dataset.data).forEach(node => node.remove());
        formUrl.search = new URLSearchParams(new FormData(form));

        {
            formSetMsg("pending");

            fetch(formUrl)
                .then(data => data.json())
                .then(data => {
                    if (!data.length) throw formGetMsg("notfound");

                    return data;
                })
                .then(data => data.reverse())
                .then(data => data.forEach(item => {
                    const tr = trTpl.content.cloneNode(true);

                    tr.querySelectorAll("*[data-content]")
                        .forEach(node => node.textContent = item[node.dataset.content]);

                    trTpl.after(tr);

                    formSetMsg("done", data.length);
                }))
                .catch(error => formSetMsg("error", error))
                .catch(error => {throw error;});
        }

        return false;
    });
}))(window);