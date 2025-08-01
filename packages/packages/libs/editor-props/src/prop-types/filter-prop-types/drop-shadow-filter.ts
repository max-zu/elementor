import { z } from '@elementor/schema';

import { createPropUtils } from '../../utils/create-prop-utils';
import { sizePropTypeUtil } from '../size';
import { unknownChildrenSchema } from '../utils';

export const dropShadowFilterPropTypeUtil = createPropUtils(
	'drop-shadow',
	z.object( {
		xAxis: sizePropTypeUtil.schema,
		yAxis: sizePropTypeUtil.schema,
		blur: sizePropTypeUtil.schema,
		color: unknownChildrenSchema,
	} )
);

export type DropShadowFilterPropValue = z.infer< typeof dropShadowFilterPropTypeUtil.schema >;
