<template>
  <div class="cptm-form-group directorist-conditional-mapping">
    <label v-if="label"><span>{{ label }}</span></label>
    <p v-if="description" class="cptm-form-group-info">{{ description }}</p>
    <div v-for="(row, index) in mappings" :key="index" class="directorist-conditional-mapping__row">
      <div class="directorist-conditional-mapping__result">
        <strong>Show</strong>
        <select v-model="row.pricing_type" @change="emitValue">
          <option v-for="option in resultOptions" :key="option.value" :value="option.value">{{ option.label }}</option>
        </select>
        <strong>if</strong>
        <button type="button" class="cptm-btn cptm-btn-danger" @click="removeRow(index)">Remove</button>
      </div>
      <conditional-logic-field
        :field-id="`${fieldId}_${index}_condition`"
        :field-key="`${fieldKey}_${index}`"
        :root="root"
        :value="row.conditional_logic"
        :hide-toggle="true"
        :hide-action="true"
        @update="updateLogic(index, $event)"
      />
    </div>
    <button type="button" class="cptm-btn cptm-btn-secondery" @click="addRow">Add Mapping</button>
  </div>
</template>

<script>
import props from '../../mixins/form-fields/input-field-props';

export default {
  name: 'conditional-mapping-field',
  mixins: [props],
  data() { return { mappings: [] }; },
  created() { this.mappings = this.normalize(this.value); },
  watch: { value: { deep: true, handler(value) { if (JSON.stringify(value) !== JSON.stringify(this.mappings)) this.mappings = this.normalize(value); } } },
  methods: {
    normalize(value) {
      return (Array.isArray(value) ? value : []).map((row) => ({
        pricing_type: ['both', 'price_unit', 'price_range'].includes(row.pricing_type) ? row.pricing_type : 'both',
        conditional_logic: { enabled: true, action: 'show', globalOperator: 'OR', groups: [], ...(row.conditional_logic || {}) },
      }));
    },
    addRow() {
      this.mappings.push({ pricing_type: 'both', conditional_logic: { enabled: true, action: 'show', globalOperator: 'OR', groups: [{ operator: 'AND', isGroup: false, conditions: [{ field: '', operator: 'is', value: '' }] }] } });
      this.emitValue();
    },
    removeRow(index) { this.mappings.splice(index, 1); this.emitValue(); },
    updateLogic(index, logic) { this.mappings[index].conditional_logic = { ...logic, enabled: true, action: 'show' }; this.emitValue(); },
    emitValue() { this.$emit('update', JSON.parse(JSON.stringify(this.mappings))); },
  },
};
</script>
