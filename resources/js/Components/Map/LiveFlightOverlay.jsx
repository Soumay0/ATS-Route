import React from 'react';
import { Marker, Popup } from 'react-leaflet';
import L from 'leaflet';
import { renderToStaticMarkup } from 'react-dom/server';
import { PlaneTakeoff } from 'lucide-react';

export default function LiveFlightOverlay({ flight }) {
    if (!flight.latitude || !flight.longitude) return null;

    const createPlaneIcon = (heading) => {
        const iconMarkup = renderToStaticMarkup(
            <div style={{ 
                transform: `rotate(${heading || 0}deg)`, 
                color: '#f59e0b', /* Eye-soothing Amber/Yellow, standard for aviation tracking */
            }}>
                <PlaneTakeoff size={14} fill="currentColor" />
            </div>
        );
        return L.divIcon({ 
            html: iconMarkup, 
            className: 'plane-icon', 
            iconSize: [14, 14], 
            iconAnchor: [7, 7],
            popupAnchor: [0, -10]
        });
    };

    return (
        <Marker 
            position={[flight.latitude, flight.longitude]}
            icon={createPlaneIcon(flight.true_track)}
        >
            <Popup className="custom-popup">
                <div className="p-1">
                    <h3 className="font-bold text-md border-b border-white/10 pb-1 mb-1">
                        Callsign: {flight.callsign || 'UNKNOWN'}
                    </h3>
                    <div className="text-xs text-text-secondary flex flex-col gap-1">
                        <div>Altitude: {flight.geo_altitude ? `${flight.geo_altitude}m` : 'N/A'}</div>
                        <div>Speed: {flight.velocity ? `${flight.velocity}m/s` : 'N/A'}</div>
                        <div>Heading: {flight.true_track ? `${flight.true_track}°` : 'N/A'}</div>
                    </div>
                </div>
            </Popup>
        </Marker>
    );
}
