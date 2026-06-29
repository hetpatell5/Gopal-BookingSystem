<style>
.field-card {
    background: white;
    border: 1px solid #e5e7eb;
    padding: 16px;
    position: relative;
    transition: border-color 0.15s;
}
.field-card:hover { border-color: #f0b44b; }
.field-card input[type=text], .field-card textarea, .field-card select {
    border: 1px solid #e5e7eb;
    padding: 6px 10px;
    font-size: 13px;
    outline: none;
    width: 100%;
    border-radius: 0;
    background: white;
    transition: border-color 0.15s;
}
.field-card input[type=text]:focus, .field-card textarea:focus, .field-card select:focus {
    border-color: #f0b44b;
}
#previewContainer .prev-header { background:white; border-top:6px solid #f0b44b; padding:20px; margin-bottom:12px; box-shadow:0 1px 3px rgba(0,0,0,.07); }
#previewContainer .prev-field  { background:white; padding:16px; margin-bottom:10px; box-shadow:0 1px 3px rgba(0,0,0,.07); font-size:13px; }
#previewContainer .prev-input  { width:100%; border-bottom:1px solid #ccc; outline:none; padding:4px 0; font-size:12px; color:#999; background:transparent; pointer-events:none; }
#previewContainer .prev-select { width:100%; border:1px solid #ccc; padding:4px 6px; font-size:12px; color:#aaa; background:white; pointer-events:none; }
</style>

<script>
let fields = {!! json_encode($initialFields ?? []) !!};
const uid  = () => Math.random().toString(36).slice(2, 11);

const ICONS = {
    short_text:'fa-font', long_text:'fa-align-left', email:'fa-envelope',
    number:'fa-hashtag', date:'fa-calendar', dropdown:'fa-list',
    multiple_choice:'fa-circle-dot', checkboxes:'fa-square-check', section_header:'fa-heading',
};
const COLORS = {
    short_text:'#3b82f6', long_text:'#6366f1', email:'#06b6d4',
    number:'#8b5cf6', date:'#f43f5e', dropdown:'#f59e0b',
    multiple_choice:'#22c55e', checkboxes:'#14b8a6', section_header:'#94a3b8',
};
const HAS_OPTIONS = ['dropdown', 'multiple_choice', 'checkboxes'];
const LABELS = {
    short_text:'Short Answer', long_text:'Paragraph', email:'Email',
    number:'Number', date:'Date', dropdown:'Dropdown',
    multiple_choice:'Multiple Choice', checkboxes:'Checkboxes', section_header:'Section Header',
};

function addField(type) {
    fields.push({ id: uid(), type, label: LABELS[type], description: '', required: false,
        options: HAS_OPTIONS.includes(type) ? ['Option 1','Option 2'] : undefined, placeholder: '' });
    renderAll();
}
function removeField(id) { fields = fields.filter(f => f.id !== id); renderAll(); }
function moveField(id, dir) {
    const idx = fields.findIndex(f => f.id === id);
    const n   = idx + dir;
    if (n < 0 || n >= fields.length) return;
    [fields[idx], fields[n]] = [fields[n], fields[idx]];
    renderAll();
}
function updateField(id, key, value) { const f=fields.find(f=>f.id===id); if(f){f[key]=value; renderPreview();} }
function addOption(id) { const f=fields.find(f=>f.id===id); if(f){f.options=[...(f.options||[]),`Option ${(f.options?.length||0)+1}`]; renderAll();} }
function updateOption(id,i,val){ const f=fields.find(f=>f.id===id); if(f&&f.options){f.options[i]=val; renderPreview();} }
function removeOption(id,i){ const f=fields.find(f=>f.id===id); if(f&&f.options){f.options.splice(i,1); renderAll();} }
function escHtml(s){ return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

function renderAll() { renderBuilder(); renderPreview(); }

function renderBuilder() {
    const list = document.getElementById('fieldList');
    list.innerHTML = '';
    if (!fields.length) {
        list.innerHTML = '<div style="text-align:center;padding:40px 0;color:#9ca3af;border:2px dashed #e5e7eb;font-size:13px;">Click a field type above to add your first field</div>';
        return;
    }
    fields.forEach((f, idx) => {
        const card = document.createElement('div');
        card.className = 'field-card';
        const isSection = f.type === 'section_header';
        const hasOpts   = HAS_OPTIONS.includes(f.type);
        card.innerHTML = `
        <div style="display:flex;align-items:flex-start;gap:12px;">
            <div style="display:flex;flex-direction:column;gap:2px;padding-top:2px;">
                <button onclick="moveField('${f.id}',-1)" ${idx===0?'disabled':''} style="background:none;border:none;cursor:pointer;color:#9ca3af;padding:2px;${idx===0?'opacity:.3;':''}">
                    <i class="fa-solid fa-chevron-up" style="font-size:10px;"></i></button>
                <i class="fa-solid fa-grip-vertical" style="font-size:12px;color:#d1d5db;display:block;text-align:center;padding:2px;"></i>
                <button onclick="moveField('${f.id}',1)" ${idx===fields.length-1?'disabled':''} style="background:none;border:none;cursor:pointer;color:#9ca3af;padding:2px;${idx===fields.length-1?'opacity:.3;':''}">
                    <i class="fa-solid fa-chevron-down" style="font-size:10px;"></i></button>
            </div>
            <div style="flex:1;min-width:0;">
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:10px;">
                    <i class="fa-solid ${ICONS[f.type]}" style="font-size:11px;color:${COLORS[f.type]};"></i>
                    <span style="font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;">${LABELS[f.type]}</span>
                </div>
                <input type="text" value="${escHtml(f.label)}" placeholder="Field label *"
                       oninput="updateField('${f.id}','label',this.value)"
                       style="font-weight:600;color:#1c2238;margin-bottom:6px;">
                ${!isSection ? `
                <input type="text" value="${escHtml(f.description||'')}" placeholder="Description (optional)"
                       oninput="updateField('${f.id}','description',this.value)"
                       style="color:#6b7280;font-size:12px;margin-bottom:6px;">
                ${['short_text','long_text','email','number'].includes(f.type)?`
                <input type="text" value="${escHtml(f.placeholder||'')}" placeholder="Placeholder text (optional)"
                       oninput="updateField('${f.id}','placeholder',this.value)"
                       style="color:#9ca3af;font-size:12px;margin-bottom:8px;font-style:italic;">
                `:''}
                ${hasOpts?`
                <div style="margin-bottom:8px;">
                    ${(f.options||[]).map((opt,i)=>`
                    <div style="display:flex;align-items:center;gap:6px;margin-bottom:4px;">
                        ${f.type==='multiple_choice'?'<i class="fa-regular fa-circle" style="font-size:11px;color:#d1d5db;"></i>':''}
                        ${f.type==='checkboxes'?'<i class="fa-regular fa-square" style="font-size:11px;color:#d1d5db;"></i>':''}
                        ${f.type==='dropdown'?`<span style="font-size:11px;color:#d1d5db;font-weight:bold;">${i+1}.</span>`:''}
                        <input type="text" value="${escHtml(opt)}" placeholder="Option ${i+1}"
                               oninput="updateOption('${f.id}',${i},this.value)"
                               style="flex:1;font-size:12px;">
                        <button onclick="removeOption('${f.id}',${i})" style="background:none;border:none;cursor:pointer;color:#d1d5db;">
                            <i class="fa-solid fa-xmark" style="font-size:11px;"></i></button>
                    </div>`).join('')}
                    <button onclick="addOption('${f.id}')" style="background:none;border:none;cursor:pointer;color:#f0b44b;font-size:12px;font-weight:600;padding:4px 0;">
                        <i class="fa-solid fa-plus" style="font-size:10px;"></i> Add option</button>
                </div>
                `:''}
                <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:#6b7280;cursor:pointer;margin-top:4px;">
                    <input type="checkbox" ${f.required?'checked':''} onchange="updateField('${f.id}','required',this.checked)"
                           style="accent-color:#f0b44b;width:14px;height:14px;">
                    Required
                </label>
                `:''}
            </div>
            <button onclick="removeField('${f.id}')" style="background:none;border:none;cursor:pointer;color:#d1d5db;padding:4px;">
                <i class="fa-solid fa-trash" style="font-size:12px;"></i></button>
        </div>`;
        list.appendChild(card);
    });
}

function renderPreview() {
    const name = document.getElementById('formName').value || 'Untitled Form';
    const desc = document.getElementById('formDescription').value;
    let html = `<div class="prev-header">
        <h1 style="font-size:18px;font-weight:bold;color:#1c2238;">${escHtml(name)}</h1>
        ${desc?`<p style="font-size:12px;color:#6b7280;margin-top:4px;">${escHtml(desc)}</p>`:''}
        <p style="font-size:11px;color:#ef4444;margin-top:8px;">* Required</p>
    </div>`;
    fields.forEach(f => {
        if (f.type === 'section_header') {
            html += `<div class="prev-field" style="border-bottom:2px solid #f0b44b;">
                <h2 style="font-size:14px;font-weight:700;color:#1c2238;">${escHtml(f.label||'Section')}</h2>
                ${f.description?`<p style="font-size:11px;color:#9ca3af;margin-top:2px;">${escHtml(f.description)}</p>`:''}
            </div>`;
        } else {
            const req = f.required ? '<span style="color:#ef4444;margin-left:2px;">*</span>' : '';
            html += `<div class="prev-field">
                <p style="font-weight:600;color:#1c2238;margin-bottom:4px;">${escHtml(f.label||'Field')}${req}</p>
                ${f.description?`<p style="font-size:11px;color:#9ca3af;margin-bottom:6px;">${escHtml(f.description)}</p>`:''}
                ${renderPreviewInput(f)}
            </div>`;
        }
    });
    if (fields.length > 0) html += `<div style="text-align:right;margin-top:12px;"><button style="background:#f0b44b;color:#1c2238;font-weight:700;font-size:12px;padding:8px 20px;border:none;opacity:.8;cursor:default;">Submit</button></div>`;
    document.getElementById('previewContainer').innerHTML = html;
}

function renderPreviewInput(f) {
    switch(f.type) {
        case 'short_text': case 'email': case 'number':
            return `<input class="prev-input" placeholder="${escHtml(f.placeholder||'Your answer')}" readonly>`;
        case 'long_text':
            return `<textarea class="prev-input" rows="2" placeholder="${escHtml(f.placeholder||'Your answer')}" readonly></textarea>`;
        case 'date':
            return `<input type="text" class="prev-input" placeholder="dd/mm/yyyy" readonly>`;
        case 'dropdown':
            return `<select class="prev-select" disabled><option>Choose an option</option>${(f.options||[]).map(o=>`<option>${escHtml(o)}</option>`).join('')}</select>`;
        case 'multiple_choice':
            return (f.options||[]).map(o=>`<label style="display:flex;align-items:center;gap:6px;margin-bottom:4px;font-size:12px;color:#6b7280;"><input type="radio" disabled style="accent-color:#f0b44b;">${escHtml(o)}</label>`).join('');
        case 'checkboxes':
            return (f.options||[]).map(o=>`<label style="display:flex;align-items:center;gap:6px;margin-bottom:4px;font-size:12px;color:#6b7280;"><input type="checkbox" disabled style="accent-color:#f0b44b;">${escHtml(o)}</label>`).join('');
        default: return '';
    }
}

function saveForm() {
    const name = document.getElementById('formName').value.trim();
    if (!name)          { alert('Please enter a form title.'); document.getElementById('formName').focus(); return; }
    if (!fields.length) { alert('Please add at least one field.'); return; }
    if (!fields.some(f => f.type !== 'section_header')) { alert('Please add at least one input field.'); return; }
    document.getElementById('hiddenName').value   = name;
    document.getElementById('hiddenDesc').value   = document.getElementById('formDescription').value;
    document.getElementById('hiddenFields').value = JSON.stringify(fields);
    document.getElementById('submitForm').submit();
}

document.getElementById('formName').addEventListener('input', renderPreview);
document.getElementById('formDescription').addEventListener('input', renderPreview);
renderAll();
</script>
