import React, { useState } from 'react';
import { Polyline, Tooltip } from 'react-leaflet';

export default function RouteLine({ route, fromWp, toWp }) {
    const [isHovered, setIsHovered] = useState(false);
    const isCongested = route.status === 'congested';
    
    // In dark mode or light mode, css variables will handle the popup background
    // but the polyline color needs to be explicitly passed.
    // Assuming dark mode is default. 
    // It is easier to just pass hardcoded colors or read them via getComputedStyle, 
    // but we can use static colors that work well in both modes.
    const routeColor = isCongested ? '#e11d48' : '#0284c7'; // using Tailwind colors
    
    return (
        <Polyline 
            positions={[[fromWp.lat, fromWp.lon], [toWp.lat, toWp.lon]]}
            color={routeColor}
            weight={isHovered ? (isCongested ? 6 : 4) : (isCongested ? 4 : 2)}
            dashArray={isCongested ? "10, 10" : "5, 5"}
            opacity={isHovered ? 1 : 0.7}
            eventHandlers={{
                mouseover: () => setIsHovered(true),
                mouseout: () => setIsHovered(false),
            }}
            className="transition-all duration-300 cursor-pointer"
        >
            <Tooltip sticky className="custom-popup border-none">
                <div className="font-medium text-sm">
                    {fromWp.id} ✈ {toWp.id}
                </div>
                <div className="text-xs opacity-80 capitalize">
                    Status: {route.status}
                </div>
            </Tooltip>
        </Polyline>
    );
}
